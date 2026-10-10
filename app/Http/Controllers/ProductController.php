<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    // Untuk menampilkan daftar produk
    public function index()
    {
         $products = Product::with('category')
            ->where('is_available', true)
            ->latest()
            ->paginate(12);

        return view('products.index', compact('products'));
    }

    //menampilkan detail produk
    public function show(Product $product)
    {
        abort_unless($product->is_available, 404);

        $product->load('category');

        return view('products.show', compact('product'));
    }
}


