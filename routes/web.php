<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomCakeController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\OrderTrackingController;
use App\Http\Controllers\CartController;


use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\CustomCakeController as AdminCustomCakeController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\CategoryController;

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

Route::get('/produk', [ProductController::class, 'index'])
    ->name('products.index');

Route::get('/produk/{product}', [ProductController::class, 'show'])
    ->name('products.show');

// Keranjang belanja

Route::get('/keranjang', [CartController::class, 'index'])
    ->name('cart.index');

Route::post('/keranjang/tambah', [CartController::class, 'add'])
    ->name('cart.add');

Route::post('/keranjang/perbarui', [CartController::class, 'update'])
    ->name('cart.update');

Route::post('/keranjang/hapus', [CartController::class, 'remove'])
    ->name('cart.remove');

// Checkout tanpa login
Route::get('/checkout', [CheckoutController::class, 'index'])
    ->name('checkout.index');

Route::post('/checkout', [CheckoutController::class, 'store'])
    ->name('checkout.store');

// Custom cake
Route::get('/custom-cake', [CustomCakeController::class, 'index'])
    ->name('custom-cake.index');

Route::post('/custom-cake', [CustomCakeController::class, 'store'])
    ->name('custom-cake.store');

// Lacak pesanan
Route::get('/lacak-pesanan', [OrderTrackingController::class, 'index'])
    ->name('orders.track');

Route::post('/lacak-pesanan', [OrderTrackingController::class, 'track'])
    ->name('orders.track.submit');

    
 // Promo
Route::get('/promo', [PromoController::class, 'index'])
    ->name('promo.index');

Route::get('/promo/{id}', [PromoController::class, 'show'])
    ->name('promo.show');

// Tentang Kami
Route::view('/tentang-kami', '.about')
    ->name('about');

// Kontak
Route::get('/kontak', [ContactController::class, 'index'])
    ->name('contact');

Route::post('/kontak', [ContactController::class, 'send'])
    ->name('contact.send');


Route::middleware(['auth', 'verified'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

Route::resource('/produk', AdminProductController::class)
    ->names('products');

Route::resource('/kategori', CategoryController::class)
    ->names('categories');

Route::get('/pesanan', [AdminOrderController::class, 'index'])
    ->name('orders.index');

Route::get('/pesanan/{order}', [AdminOrderController::class, 'show'])
    ->name('orders.show');

Route::patch('/pesanan/{order}/status', [AdminOrderController::class, 'updateStatus'])
    ->name('orders.update-status');

Route::get('/custom-cakes', [AdminCustomCakeController::class, 'index'])
    ->name('custom-cakes.index');

Route::get('/custom-cakes/{customCake}', [AdminCustomCakeController::class, 'show'])
    ->name('custom-cakes.show');

Route::patch('/custom-cakes/{customCake}/harga', [AdminCustomCakeController::class, 'updatePrice'])
    ->name('custom-cakes.update-price');

Route::patch('/custom-cakes/{customCake}/status', [AdminCustomCakeController::class, 'updateStatus'])
    ->name('custom-cakes.update-status');

Route::get('/pembayaran', [AdminPaymentController::class, 'index'])
    ->name('payments.index');

Route::patch('/pembayaran/{payment}/status', [AdminPaymentController::class,'updateStatus',])
    ->name('payments.update-status');

    });

require __DIR__.'/auth.php';