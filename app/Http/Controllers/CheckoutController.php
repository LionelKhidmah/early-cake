<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    
public function store(Request $request)
{
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'phone' => ['required', 'string', 'max:20'],
        'email' => ['nullable', 'email', 'max:255'],
        'address' => ['required', 'string'],
        'notes' => ['nullable', 'string'],

        'payment_method' => [
            'required',
            'in:transfer,cash,qris',
        ],

        'delivery_method' => [
            'required',
            'in:gofood,grabfood,shopeefood',
        ],

        'items' => ['required', 'array', 'min:1'],
        'items.*.product_id' => [
            'required',
            'integer',
            'distinct',
            'exists:products,id',
        ],
        'items.*.quantity' => [
            'required',
            'integer',
            'min:1',
        ],
    ]);

    $order = DB::transaction(function () use ($validated) {
        // Simpan identitas pelanggan tanpa akun login.
        $customer = Customer::create([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'] ?? null,
            'address' => $validated['address'],
        ]);

        $totalAmount = 0;
        $orderItems = [];

        // Kunci baris produk selama transaksi agar stok
        // diperiksa kembali sebelum dikurangi.
        foreach ($validated['items'] as $item) {
            $product = Product::whereKey($item['product_id'])
                ->lockForUpdate()
                ->first();

            if (
                ! $product ||
                ! $product->is_available ||
                $product->stock < $item['quantity']
            ) {
                throw ValidationException::withMessages([
                    'items' => 'Salah satu produk tidak tersedia atau stoknya tidak mencukupi.',
                ]);
            }

            $price = (float) $product->price;
            $subtotal = $price * $item['quantity'];

            $totalAmount += $subtotal;

            $orderItems[] = [
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'price' => $price,
                'subtotal' => $subtotal,
            ];

            $product->decrement('stock', $item['quantity']);
        }

        // Buat nomor pesanan unik.
        do {
            $orderNumber = 'EC-' . Str::upper(Str::random(10));
        } while (Order::where('order_number', $orderNumber)->exists());

        $order = Order::create([
            'customer_id' => $customer->id,
            'order_number' => $orderNumber,
            'order_date' => now(),
            'delivery_address' => $validated['address'],
            'notes' => $validated['notes'] ?? null,
            'total_amount' => $totalAmount,
            'status' => 'pending',
        ]);

        foreach ($orderItems as $item) {
            $order->items()->create($item);
        }

        $order->payment()->create([
            'payment_method' => $validated['payment_method'],
            'amount' => $totalAmount,
            'payment_status' => 'pending',
        ]);

        $order->delivery()->create([
            'delivery_method' => $validated['delivery_method'],
            'delivery_status' => 'pending',
            'delivery_address' => $validated['address'],
        ]);

        return $order;
    });

    return redirect()
        ->route('orders.track')
        ->with('order_number', $order->order_number)
        ->with('success', 'Pesanan berhasil dibuat.');
    }
}
