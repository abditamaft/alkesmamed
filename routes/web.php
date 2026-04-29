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
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Middleware\IsAdmin;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\HomeSettingController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;

// ==========================================================
// 1. RUTE PUBLIK (Bebas akses)
// ==========================================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/produk', [ProductController::class, 'index'])->name('produk.index');
Route::get('/detail-produk/{id}', [ProductController::class, 'show'])->name('produk.show');
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{id}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/kontak', [ContactController::class, 'index'])->name('kontak.index');
Route::get('/api/blog/search', [BlogController::class, 'searchApi'])->name('api.blog.search');
Route::get('/api/produk/search', [App\Http\Controllers\ProductController::class, 'searchApi']);

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
Route::prefix('admin')->group(function () {
    
    // Rute Login (Guest)
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');

    // Rute yang Dilindungi (Wajib Login & Wajib Admin)
    Route::middleware([IsAdmin::class])->group(function () {
        
        // GANTI RUTE /admin MENJADI INI:
        Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');

        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
        
        // 👇 TAMBAHKAN 3 BARIS INI UNTUK KATEGORI 👇
        Route::get('/kategori', [CategoryController::class, 'index'])->name('admin.categories.index');
        Route::post('/kategori', [CategoryController::class, 'store'])->name('admin.categories.store');
        Route::get('/kategori/{id}/edit', [CategoryController::class, 'edit'])->name('admin.categories.edit');
        Route::put('/kategori/{id}', [CategoryController::class, 'update'])->name('admin.categories.update');
        Route::delete('/kategori/{id}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');
        // Rute AJAX Produk (Realtime Status & Search)
        Route::post('/produk/{id}/toggle-status', [AdminProductController::class, 'toggleStatus'])->name('admin.products.toggle');
        // 👇 TAMBAHKAN BARIS INI 👇
        Route::post('/produk/{id}/toggle-flash-sale', [AdminProductController::class, 'toggleFlashSale'])->name('admin.products.toggleFlashSale');
        Route::get('/produk/search', [AdminProductController::class, 'searchAdmin'])->name('admin.products.search');
        // CRUD PRODUK
        Route::resource('produk', AdminProductController::class, [
            'names' => 'admin.products'
        ]);
        // AJAX Badge Sidebar
        Route::get('/pesanan/badge', [App\Http\Controllers\Admin\OrderController::class, 'badgeCount'])->name('admin.orders.badge');
        
        // Rute Order
        Route::resource('pesanan', App\Http\Controllers\Admin\OrderController::class)->names('admin.orders');
        // MANAJEMEN ONGKIR & WILAYAH
        Route::get('/ongkir', [App\Http\Controllers\Admin\ShippingController::class, 'index'])->name('admin.shipping.index');
        Route::get('/ongkir/search', [App\Http\Controllers\Admin\ShippingController::class, 'search'])->name('admin.shipping.search');
        Route::post('/ongkir/provinsi', [App\Http\Controllers\Admin\ShippingController::class, 'storeProvince'])->name('admin.shipping.storeProvince');
        Route::put('/ongkir/provinsi/{id}', [App\Http\Controllers\Admin\ShippingController::class, 'updateProvince'])->name('admin.shipping.updateProvince');
        Route::delete('/ongkir/provinsi/{id}', [App\Http\Controllers\Admin\ShippingController::class, 'destroyProvince'])->name('admin.shipping.destroyProvince');
        
        Route::get('/ongkir/{id}', [App\Http\Controllers\Admin\ShippingController::class, 'showProvince'])->name('admin.shipping.show');
        Route::post('/ongkir/{id}/kota', [App\Http\Controllers\Admin\ShippingController::class, 'storeCity'])->name('admin.shipping.storeCity');
        Route::put('/ongkir/kota/{id}', [App\Http\Controllers\Admin\ShippingController::class, 'updateCity'])->name('admin.shipping.updateCity');
        Route::delete('/ongkir/kota/{id}', [App\Http\Controllers\Admin\ShippingController::class, 'destroyCity'])->name('admin.shipping.destroyCity');
        // MANAJEMEN ARTIKEL / BLOG
        Route::resource('kategori-blog', App\Http\Controllers\Admin\BlogCategoryController::class)
             ->names('admin.blog_categories')
             ->except(['create', 'show', 'edit']); // Kita matikan view yang tidak dipakai karena kita pakai sistem 1 Halaman
        // CRUD SEMUA ARTIKEL
        Route::post('/blogs/{id}/toggle-status', [App\Http\Controllers\Admin\BlogPostController::class, 'toggleStatus'])->name('admin.blogs.toggle');
        Route::get('/blogs/search', [App\Http\Controllers\Admin\BlogPostController::class, 'searchAdmin'])->name('admin.blogs.search');
        Route::resource('blogs', App\Http\Controllers\Admin\BlogPostController::class)->names('admin.blogs');
        // PENGATURAN HALAMAN DEPAN (HOME)
        // PENGATURAN HALAMAN DEPAN (HOME)
        Route::get('/home-setting', [\App\Http\Controllers\Admin\HomeSettingController::class, 'index'])->name('admin.home.index');
        Route::post('/home-setting/about', [\App\Http\Controllers\Admin\HomeSettingController::class, 'updateAbout'])->name('admin.home.updateAbout');
        Route::post('/home-setting/banner', [\App\Http\Controllers\Admin\HomeSettingController::class, 'storeBanner'])->name('admin.home.storeBanner');
        Route::delete('/home-setting/banner/{id}', [\App\Http\Controllers\Admin\HomeSettingController::class, 'destroyBanner'])->name('admin.home.destroyBanner');
    });  
});