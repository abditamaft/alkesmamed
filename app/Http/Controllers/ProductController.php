<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductVariant;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['images', 'mainImage', 'category', 'variants'])->where('is_active', 1);

        // REVISI: Ubah has menjadi filled agar tidak error jika dikosongi
        if ($request->filled('kategori')) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->kategori);
            });
        }

        // REVISI: Ubah has menjadi filled
        if ($request->filled('ukuran')) {
            $query->whereHas('variants', function($q) use ($request) {
                $q->where('variant_name', $request->ukuran);
            });
        }

        if ($request->filled('min_price') || $request->filled('max_price')) {
            $min = $request->filled('min_price') ? $request->min_price : 0;
            $max = $request->filled('max_price') ? $request->max_price : 999999999;

            $query->whereHas('variants', function($q) use ($min, $max) {
                $q->whereBetween('price', [$min, $max]);
            });
        }

        $allProducts = $query->paginate(60);
        $categories = Category::withCount('products')->get(); 
        $sizes = ProductVariant::select('variant_name')->distinct()->get();

        $newestProductIds = Product::where('is_active', 1)->orderBy('created_at', 'desc')->take(3)->pluck('id')->toArray();

        return view('produk', compact('allProducts', 'categories', 'sizes', 'newestProductIds'));
    }

    // Fungsi show() untuk Detail Produk (Kita biarkan dulu atau sesuaikan nanti)
    public function show($id)
    {
        // 1. Cari Produk Utama beserta SEMUA relasinya
        $product = Product::with(['images', 'variants', 'category'])->findOrFail($id);

        // 2. Ambil Data Produk Serupa (Satu Kategori)
        $related_products = Product::with(['mainImage', 'category', 'variants'])
                            ->where('category_id', $product->category_id)
                            ->where('id', '!=', $id)
                            ->get(); // Ambil semua untuk di-slider

        // 3. Produk Unggulan (Karena tabel 'orders' belum ada isinya, kita akali ambil 3 produk terbaru/acak dulu)
        $featured_products = Product::with(['mainImage', 'variants'])
                             ->where('is_active', 1)
                             ->inRandomOrder()
                             ->take(3)
                             ->get();

        return view('detail', compact('product', 'related_products', 'featured_products'));
    }
    // 4. Fungsi Baru untuk Live Search Sidebar
    public function searchApi(Request $request)
    {
        $query = $request->get('q');
        
        if (strlen($query) < 2) return response()->json([]); // Minimal 2 huruf

        $products = Product::with(['mainImage', 'variants'])
            ->where('name', 'LIKE', "%{$query}%")
            ->where('is_active', 1)
            ->take(5) // Tampilkan max 5 rekomendasi
            ->get()
            ->map(function($p) {
                return [
                    'name' => $p->name,
                    'price' => 'Rp' . number_format($p->starting_price, 0, ',', '.'),
                    'image' => $p->mainImage ? asset('images/' . $p->mainImage->image_path) : asset('images/default.jpg'),
                    'url' => route('produk.show', $p->id)
                ];
            });

        return response()->json($products);
    }
    
}
