<?php

use Illuminate\Support\Facades\Route;

// Customer Controllers
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomCakeController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\OrderTrackingController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CustomCakeNotificationController;
use App\Http\Controllers\CustomCakeStatusController;

// Password Reset Controllers
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CustomCakeOptionController as AdminCustomCakeOptionController;
use App\Http\Controllers\Admin\PromoController as AdminPromoController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\CustomCakeController as AdminCustomCakeController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\CategoryController;

/*
|--------------------------------------------------------------------------
| CUSTOMER ROUTES
|--------------------------------------------------------------------------
*/

// Home
Route::get('/', [HomeController::class, 'index'])
    ->name('home');

// Produk
Route::get('/produk', [ProductController::class, 'index'])
    ->name('products.index');

Route::get('/produk/{product}', [ProductController::class, 'show'])
    ->name('products.show');

// Keranjang
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

// Custom Cake
Route::get('/custom-cake', [CustomCakeController::class, 'index'])
    ->name('custom-cake.index');

Route::post('/custom-cake', [CustomCakeController::class, 'store'])
    ->name('custom-cake.store');

// Lacak Pesanan
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
Route::view('/tentang-kami', 'about')
    ->name('about');

// Kontak
Route::get('/kontak', [ContactController::class, 'index'])
    ->name('contact');

Route::post('/kontak', [ContactController::class, 'send'])
    ->name('contact.send');

// Notifikasi Custom Cake
Route::get('/notifikasi-custom-cake/{token}', [
    CustomCakeNotificationController::class,
    'index',
])->name('custom-cake.notifications');

Route::get(
    '/notifikasi-custom-cake/status/{orderNumber}/{customCakeId}',
    [CustomCakeNotificationController::class, 'openStatus']
)->middleware('signed')
 ->name('custom-cake.notifications.status');

Route::patch('/notifikasi-custom-cake/{token}/{notification}/baca', [
    CustomCakeNotificationController::class,
    'markAsRead',
])->name('custom-cake.notifications.read');

Route::post(
    '/notifikasi-custom-cake/{token}/subscribe',
    [CustomCakeNotificationController::class, 'subscribe']
)->name('custom-cake.notifications.subscribe');

// Cek Status Custom Cake
Route::get('/custom-cake/cek-status', [
    CustomCakeStatusController::class,
    'index',
])->name('custom-cake.status.check');

Route::post('/custom-cake/cek-status', [
    CustomCakeStatusController::class,
    'lookup',
])->name('custom-cake.status.lookup');

Route::get('/custom-cake/status/{orderNumber}', [
    CustomCakeStatusController::class,
    'show',
])->name('custom-cake.status.show');

Route::post('/custom-cake/status/{orderNumber}/desain-ulang', [CustomCakeStatusController::class,'redesign',])
            ->name('custom-cake.redesign');

/*
|--------------------------------------------------------------------------
| ADMIN AUTHENTICATION
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Hanya untuk pengguna yang belum login
        Route::middleware('guest')->group(function () {

            // Login Admin
            Route::get('/login', [AdminAuthController::class, 'create'])
                ->name('login');

            Route::post('/login', [AdminAuthController::class, 'store'])
                ->middleware('throttle:5,1')
                ->name('login.store');

            // Lupa Password Admin
            Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
                ->name('password.request');

            Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
                ->middleware('throttle:5,1')
                ->name('password.email');

            // Reset Password Admin
            Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
                ->name('password.reset');

            Route::post('/reset-password', [NewPasswordController::class, 'store'])
                ->middleware('throttle:5,1')
                ->name('password.update');
        });

        // Logout Admin
        Route::post('/logout', [AdminAuthController::class, 'destroy'])
            ->middleware(['auth', 'admin'])
            ->name('logout');
    });

/*
|--------------------------------------------------------------------------
| ADMIN DASHBOARD & MANAGEMENT
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        // Produk
        Route::resource('/produk', AdminProductController::class)
            ->names('products');

        // Kategori
        Route::post('/kategori/quick-store', [CategoryController::class, 'storeQuick'])
            ->name('categories.quick-store');

        Route::resource('/kategori', CategoryController::class)
            ->names('categories');

        // Pesanan
        Route::get('/pesanan', [AdminOrderController::class, 'index'])
            ->name('orders.index');

        Route::get('/pesanan/{order}', [AdminOrderController::class, 'show'])
            ->name('orders.show');

        Route::patch('/pesanan/{order}/status', [AdminOrderController::class, 'updateStatus'])
            ->name('orders.update-status');

        // Custom Cakes
        Route::get('/custom-cakes', [AdminCustomCakeController::class, 'index'])
            ->name('custom-cakes.index');

        Route::get('/custom-cakes/{customCake}', [AdminCustomCakeController::class, 'show'])
            ->name('custom-cakes.show');

        Route::patch('/custom-cakes/{customCake}/harga', [AdminCustomCakeController::class, 'updatePrice'])
            ->name('custom-cakes.update-price');

        Route::patch('/custom-cakes/{customCake}/status', [AdminCustomCakeController::class, 'updateStatus'])
            ->name('custom-cakes.update-status');

        Route::delete('/custom-cakes/{customCake}', [AdminCustomCakeController::class, 'destroy'])
            ->name('custom-cakes.destroy');

        // Pengaturan Pilihan Custom Cake
        Route::resource('/custom-cake-options', AdminCustomCakeOptionController::class)
            ->except(['show'])
            ->names('custom-cake-options');

        // Pembayaran
        Route::get('/pembayaran', [AdminPaymentController::class, 'index'])
            ->name('payments.index');

        Route::patch('/pembayaran/{payment}/status', [AdminPaymentController::class, 'updateStatus'])
            ->name('payments.update-status');
        
        Route::resource('promos', AdminPromoController::class);
        
    });

// Route autentikasi Breeze dinonaktifkan.
// require __DIR__.'/auth.php';
