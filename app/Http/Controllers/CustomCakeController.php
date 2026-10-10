<?php

namespace App\Http\Controllers;

use App\Models\CustomCake;
use App\Models\Order;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CustomCakeController extends Controller
{
    /**
     * Menampilkan halaman pengajuan custom cake.
     */
    public function index()
    {
        return view('custom-cakes.index');
    }

    /**
     * Menyimpan pengajuan custom cake dari pelanggan.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:100'],
            'address' => ['required', 'string', 'max:255'],

            'cake_type' => ['required', 'string', 'max:100'],
            'size' => ['required', 'string', 'max:50'],
            'flavor' => ['required', 'string', 'max:100'],
            'theme' => ['nullable', 'string', 'max:150'],
            'message' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'reference_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);

        $order = DB::transaction(function () use ($request, $validated) {
            // Simpan identitas pelanggan.
            $customer = Customer::create([
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'email' => $validated['email'] ?? null,
                'address' => $validated['address'],
            ]);

            // Buat pesanan dengan harga awal nol.
            $order = Order::create([
                'customer_id' => $customer->id,
                'order_number' => 'EC-' . Str::upper(Str::random(10)),
                'order_date' => now(),
                'delivery_address' => $validated['address'],
                'notes' => 'Pengajuan custom cake. Menunggu peninjauan admin.',
                'total_amount' => 0,
                'status' => 'pending',
            ]);

            // Simpan gambar referensi jika pelanggan mengunggahnya.
            $imagePath = null;

            if ($request->hasFile('reference_image')) {
                $imagePath = $request->file('reference_image')
                    ->store('custom-cakes', 'public');
            }

            // Simpan detail custom cake.
            CustomCake::create([
                'order_id' => $order->id,
                'cake_type' => $validated['cake_type'],
                'size' => $validated['size'],
                'flavor' => $validated['flavor'],
                'theme' => $validated['theme'] ?? null,
                'message' => $validated['message'] ?? null,
                'reference_image' => $imagePath,
                'description' => $validated['description'] ?? null,
                'estimated_price' => null,
                'final_price' => null,
                'status' => 'pending',
            ]);

            return $order;
        });

        return redirect()
            ->route('orders.track')
            ->with('order_number', $order->order_number)
            ->with('success', 'Pengajuan custom cake berhasil dikirim!');
    }
}

