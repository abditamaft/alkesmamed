<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\Product;
use App\Models\Category;
use App\Models\Page;
use App\Models\BlogPost;
use App\Models\BlogCategory;

class HomeController extends Controller
{
    public function index()
    {
        // 1. Ambil Banner Utama (Slider)
        $banners = Banner::where('is_active', 1)->latest()->get();

        // 2. Ambil 2 Produk Promo untuk kotak kecil di sebelah kanan Banner
        $promoProducts = Product::with(['mainImage', 'variants', 'category'])
            ->where('is_active', 1)->inRandomOrder()->take(2)->get();

        // 3. Ambil Produk Flash Sale
        $flashSales = Product::with(['mainImage', 'variants', 'category'])
            ->where('is_active', 1)->where('is_flash_sale', 1)->get();

        // 4. Ambil 3 Kategori Unggulan (Berdasarkan jumlah produk terbanyak)
        $topCategories = Category::withCount('products')
            ->orderBy('products_count', 'desc')->take(3)->get();

        // 5. Ambil data Tentang Kami
        $aboutUs = Page::where('slug', 'tentang-kami')->first();

        // 6. Ambil Blog & Kategori Blog
        $latestBlogs = BlogPost::with('category')->where('is_published', 1)->latest()->take(5)->get();
        $topBlogCategories = BlogCategory::withCount('posts')->orderBy('posts_count', 'desc')->take(3)->get();

        return view('home', compact(
            'banners', 'promoProducts', 'flashSales', 
            'topCategories', 'aboutUs', 'latestBlogs', 'topBlogCategories'
        ));
    }
}