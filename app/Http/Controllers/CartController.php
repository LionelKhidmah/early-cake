<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CartController extends Controller
{
    /**
     * Batas jumlah produk per item di keranjang.
     */
    private const MAX_QUANTITY = 20;

    /**
     * Menampilkan halaman keranjang.
     */
    public function index(): View
    {
        $cart = session()->get('cart', []);
        $lines = [];
        $total = 0;

        foreach ($cart as $productId => $quantity) {
            $product = Product::find($productId);

            if (! $product) {
                continue;
            }

            $quantity = (int) $quantity;
            $subtotal = (float) $product->price * $quantity;

            $lines[] = [
                'key' => (string) $product->id,
                'product' => $product,
                'quantity' => $quantity,
                'subtotal' => $subtotal,
            ];

            $total += $subtotal;
        }

        return view('cart.index', [
            'lines' => $lines,
            'total' => $total,
            'maxQuantity' => self::MAX_QUANTITY,
        ]);
    }

    /**
     * Menambahkan produk ke keranjang.
     */
    public function add(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:' . self::MAX_QUANTITY],
        ]);

        $product = Product::findOrFail($validated['product_id']);

        if (! $product->is_available || $product->stock < 1) {
            return back()->withErrors([
                'cart' => 'Produk sedang tidak tersedia.',
            ]);
        }

        $cart = session()->get('cart', []);
        $productId = (string) $product->id;
        $quantity = (int) $validated['quantity'];
        $newQuantity = ($cart[$productId] ?? 0) + $quantity;

        if ($newQuantity > self::MAX_QUANTITY) {
            return back()->withErrors([
                'cart' => 'Jumlah maksimal produk dalam keranjang adalah '
                    . self::MAX_QUANTITY . '.',
            ]);
        }

        if ($newQuantity > $product->stock) {
            return back()->withErrors([
                'cart' => 'Jumlah yang dipilih melebihi stok yang tersedia.',
            ]);
        }

        $cart[$productId] = $newQuantity;

        session()->put('cart', $cart);

        return redirect()
            ->route('cart.index')
            ->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    /**
     * Memperbarui jumlah produk dalam keranjang.
     */
    	public function update(Request $request): \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
	{
    $validated = $request->validate([
        'key' => ['required', 'integer', 'exists:products,id'],
        'quantity' => ['required', 'integer', 'min:1', 'max:' . self::MAX_QUANTITY],
    ]);

    $product = Product::findOrFail($validated['key']);
    $cart = session()->get('cart', []);
    $productId = (string) $product->id;

    if (! array_key_exists($productId, $cart)) {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Produk tidak ditemukan dalam keranjang.',
            ], 422);
        }

        return redirect()->route('cart.index')
            ->withErrors(['cart' => 'Produk tidak ditemukan dalam keranjang.']);
    }

    $quantity = (int) $validated['quantity'];

    if (! $product->is_available || $quantity > $product->stock) {
        $message = 'Jumlah melebihi stok yang tersedia atau produk tidak tersedia.';

        if ($request->expectsJson()) {
            return response()->json(['message' => $message], 422);
        }

        return redirect()->route('cart.index')
            ->withErrors(['cart' => $message]);
    }

    $cart[$productId] = $quantity;
    session()->put('cart', $cart);

    // Hitung kembali total dari harga database.
    $total = 0;

    foreach ($cart as $id => $qty) {
        $item = Product::find($id);

        if ($item && $item->is_available) {
            $total += (float) $item->price * (int) $qty;
        }
    }

    $subtotal = (float) $product->price * $quantity;

    if ($request->expectsJson()) {
        return response()->json([
            'message' => 'Keranjang berhasil diperbarui.',
            'quantity' => $quantity,
            'subtotal' => number_format($subtotal, 0, ',', '.'),
            'total' => number_format($total, 0, ',', '.'),
            'cart_count' => array_sum($cart),
        ]);
    }

    return redirect()->route('cart.index')
        ->with('success', 'Jumlah produk berhasil diperbarui.');
}

    /**
     * Menghapus produk dari keranjang.
     */
    public function remove(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'key' => ['required', 'integer'],
        ]);

        $cart = session()->get('cart', []);
        $productId = (string) $validated['key'];

        unset($cart[$productId]);

        session()->put('cart', $cart);

        return redirect()
            ->route('cart.index')
            ->with('success', 'Produk dihapus dari keranjang.');
    }
}