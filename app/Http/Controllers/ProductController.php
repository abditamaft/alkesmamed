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
        // 1. Siapkan Query Dasar (Ambil produk yang aktif beserta relasinya)
        $query = Product::with(['images', 'mainImage', 'category', 'variants'])->where('is_active', 1);

        // 2. Logika Filter Kategori
        if ($request->has('kategori')) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->kategori);
            });
        }

        // 3. Logika Filter Ukuran (Pengganti Brands)
        if ($request->has('ukuran')) {
            $query->whereHas('variants', function($q) use ($request) {
                $q->where('variant_name', $request->ukuran);
            });
        }

        // 4. Logika Filter Harga Minimal & Maksimal yang Lebih Cerdas
        if ($request->filled('min_price') || $request->filled('max_price')) {
            // Jika min kosong, paksa jadi 0. Jika max kosong, paksa jadi 999 juta.
            $min = $request->filled('min_price') ? $request->min_price : 0;
            $max = $request->filled('max_price') ? $request->max_price : 999999999;

            $query->whereHas('variants', function($q) use ($min, $max) {
                $q->whereBetween('price', [$min, $max]);
            });
        }

        // 5. Eksekusi Query dengan Paginasi (Misal 8 produk per halaman)
        $allProducts = $query->paginate(60);

        // 6. Ambil data untuk Sidebar Kiri
        // Mengambil semua kategori beserta jumlah produk di dalamnya
        $categories = Category::withCount('products')->get(); 
        
        // Mengambil daftar nama ukuran yang ada (Small, Medium, dst) unik tidak dobel
        $sizes = ProductVariant::select('variant_name')->distinct()->get();

        return view('produk', compact('allProducts', 'categories', 'sizes'));
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
