<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        // Mengambil 4 produk terbaru yang tersedia.
        $products = Product::query()
            ->where('is_available', true)
            ->latest()
            ->take(4)
            ->get();

        // Mengambil kategori beserta satu foto produk
        // yang tersedia untuk dijadikan thumbnail.
        $categories = Category::query()
            ->with([
                'products' => function ($query) {
                    $query
                        ->where('is_available', true)
                        ->whereNotNull('image')
                        ->where('image', '!=', '')
                        ->latest('id')
                        ->limit(1);
                },
            ])
            ->orderBy('name')
            ->get();

        return view('home', compact(
            'products',
            'categories'
        ));
    }
}
