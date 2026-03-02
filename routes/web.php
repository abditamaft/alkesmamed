<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProfileController;

// ==========================================================
// 1. RUTE PUBLIK (Bisa diakses siapa saja tanpa login)
// ==========================================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/produk', [ProductController::class, 'index'])->name('produk.index');
Route::get('/detail-produk/{id}', [ProductController::class, 'show'])->name('produk.show');
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{id}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/kontak', [ContactController::class, 'index'])->name('kontak.index');
Route::get('/api/search-products', [ProductController::class, 'searchApi'])->name('api.search');


// ==========================================================
// 2. RUTE GUEST (Hanya bisa diakses jika BELUM login)
// ==========================================================
Route::middleware('guest')->group(function () {
    // Halaman Login & Register
    Route::get('/login-register', [AuthController::class, 'index'])->name('login');
    
    // Proses Form
    Route::post('/login', [AuthController::class, 'login'])->name('login.perform');
    Route::post('/register', [AuthController::class, 'register'])->name('register.perform');

    // Rute Google SSO
    Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.login');
    Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);
});


// ==========================================================
// 3. RUTE AUTH (HANYA BISA DIAKSES JIKA SUDAH LOGIN)
// ==========================================================
Route::middleware(['auth'])->group(function () {
    // Proses Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Profil & Area Member
    Route::get('/profil', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profil/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/api/cities/{province_id}', [ProfileController::class, 'getCities']);
    
    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/tambah', [WishlistController::class, 'store'])->name('wishlist.store');
    Route::delete('/wishlist/hapus/{id}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');
    Route::post('/wishlist/hapus-massal', [WishlistController::class, 'destroyBulk'])->name('wishlist.destroyBulk');
    
    // Keranjang Belanja
    Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
    Route::post('/keranjang/tambah', [CartController::class, 'store'])->name('cart.store');
    Route::post('/keranjang/update', [CartController::class, 'updateBulk'])->name('cart.updateBulk');
    Route::delete('/keranjang/hapus/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::post('/keranjang/hapus-massal', [CartController::class, 'destroyBulk'])->name('cart.destroyBulk');

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::post('/profile/order/{id}/cancel', [App\Http\Controllers\ProfileController::class, 'cancelOrder'])->name('order.cancel');
});