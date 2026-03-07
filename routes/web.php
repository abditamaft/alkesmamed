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
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

// ==========================================================
// 1. RUTE PUBLIK (Bebas akses)
// ==========================================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/produk', [ProductController::class, 'index'])->name('produk.index');
Route::get('/detail-produk/{id}', [ProductController::class, 'show'])->name('produk.show');
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{id}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/kontak', [ContactController::class, 'index'])->name('kontak.index');
Route::get('/api/search-products', [ProductController::class, 'searchApi'])->name('api.search');
Route::get('/api/blog/search', [BlogController::class, 'searchApi'])->name('api.blog.search');

// ==========================================================
// 2. RUTE GUEST (Hanya sebelum login)
// ==========================================================
Route::middleware('guest')->group(function () {
    Route::get('/login-register', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.perform');
    Route::post('/register', [AuthController::class, 'register'])->name('register.perform');
    Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.login');
    Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);
});

// ==========================================================
// 3. RUTE AUTH (Wajib Login, tapi belum tentu verified)
// ==========================================================
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/email/verify', function (\Illuminate\Http\Request $request) {
        $user = $request->user();
        // Ambil waktu dari Cache. Jika tidak ada, hitung 60 menit dari waktu dia daftar
        $expiresAt = \Illuminate\Support\Facades\Cache::get('verify_timer_' . $user->id, $user->created_at->addMinutes(60));
        
        return view('auth.verify-email', compact('expiresAt'));
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect('/profil')->with('success', 'Email berhasil diverifikasi!');
    })->middleware(['signed'])->name('verification.verify');

    Route::post('/email/verification-notification', function (\Illuminate\Http\Request $request) {
        $request->user()->sendEmailVerificationNotification();
        
        // Reset waktu kedaluwarsa jadi 60 menit dari sekarang
        \Illuminate\Support\Facades\Cache::put('verify_timer_' . $request->user()->id, now()->addMinutes(60), now()->addMinutes(60));
        
        return back()->with('message', 'Link verifikasi baru telah dikirim! Waktu direset.');
    })->middleware(['throttle:6,1'])->name('verification.send');

    // API Kota (dibutuhkan di halaman profil untuk melengkapi data sebelum belanja)
    Route::get('/api/cities/{province_id}', [ProfileController::class, 'getCities']);
    Route::post('/profil/update', [ProfileController::class, 'update'])->name('profile.update');
});

// ==========================================================
// 4. RUTE VERIFIED (Wajib Login & Wajib Klik Link di Email)
// ==========================================================
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Halaman Profil (Utama)
    Route::get('/profil', [ProfileController::class, 'index'])->name('profile.index');
    
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

    // Checkout & Order
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::post('/profile/order/{id}/cancel', [ProfileController::class, 'cancelOrder'])->name('order.cancel');
});