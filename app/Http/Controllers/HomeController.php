<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
{
    $products = Product::with('category')
        ->where('is_available', true)
        ->latest()
        ->take(4)
        ->get();

    return view('home', compact('products'));
}
}