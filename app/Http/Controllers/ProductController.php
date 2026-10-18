<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    // Menampilkan daftar produk
    public function index(Request $request): View
    {
        $query = Product::with('category')
            ->where('is_available', true);

        // Pencarian nama dan deskripsi produk
        if ($request->filled('search')) {
            $search = trim($request->input('search'));

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // Filter kategori berdasarkan ID kategori
        if ($request->filled('category')) {
            $query->where('category_id', $request->input('category'));
        }

        // Pengurutan produk
        switch ($request->input('sort', 'latest')) {
            case 'price-asc':
                $query->orderBy('price', 'asc');
                break;

            case 'price-desc':
                $query->orderBy('price', 'desc');
                break;

            case 'name':
                $query->orderBy('name', 'asc');
                break;

            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(12)->withQueryString();

        $categories = Category::query()
            ->orderBy('name')
            ->get();

        return view('customer.products.index', compact(
            'products',
            'categories'
        ));
    }

    // Menampilkan detail produk
    public function show(Product $product): View
    {
        abort_unless($product->is_available, 404);

        $product->load('category');

        return view('customer.products.show', compact('product'));
    }
}
