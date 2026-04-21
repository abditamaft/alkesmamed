<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    // 1. TAMPILKAN DAFTAR PRODUK
    public function index(Request $request)
    {
        $query = Product::with(['category', 'mainImage', 'variants'])->latest();

        // Jika ada request pencarian (tekan Enter)
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhereHas('category', function($q) use ($request) {
                      $q->where('name', 'like', '%' . $request->search . '%');
                  });
        }

        $products = $query->paginate(20)->withQueryString();
        
        return view('admin.products.index', compact('products'));
    }

    // 2. TAMPILKAN FORM TAMBAH
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    // 3. PROSES SIMPAN KE 3 TABEL SEKALIGUS
    public function store(Request $request)
    {
        // Validasi Dasar
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048', // Maksimal 2MB per gambar
            'variants.*.name' => 'required|string',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.stock' => 'required|numeric|min:0',
            'variants.*.weight' => 'required|numeric|min:0',
        ]);

        // A. SIMPAN KE TABEL PRODUCTS
        $product = Product::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . time(), // Tambah time() agar slug unik
            'description' => $request->description,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        // B. SIMPAN KE TABEL PRODUCT_IMAGES (Maksimal 5 gambar yang diupload)
        if ($request->hasFile('images')) {
            $images = array_slice($request->file('images'), 0, 5); // Batasi maksimal 5 gambar
            
            foreach ($images as $index => $file) {
                $imageName = time() . '-' . uniqid() . '.' . $file->extension();
                $file->move(public_path('images'), $imageName);
                
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $imageName,
                    'is_main' => $index === 0 ? 1 : 0, // Gambar pertama otomatis jadi sampul utama
                ]);
            }
        }

        // C. SIMPAN KE TABEL PRODUCT_VARIANTS
        if ($request->has('variants')) {
            foreach ($request->variants as $variant) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'variant_name' => $variant['name'],
                    'price' => $variant['price'],
                    'stock' => $variant['stock'],
                    'weight_gram' => $variant['weight'],
                    'sku' => $variant['sku'] ?? null,
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Produk, Gambar, dan Varian berhasil disimpan!');
    }

    // 4. HAPUS PRODUK (Otomatis hapus gambar di folder)
    public function destroy($id)
    {
        $product = Product::with('images')->findOrFail($id);

        // Hapus file fisik gambar dari server
        foreach ($product->images as $img) {
            if (file_exists(public_path('images/' . $img->image_path))) {
                unlink(public_path('images/' . $img->image_path));
            }
        }

        $product->delete(); // Varian & Gambar di database akan ikut terhapus otomatis jika pakai cascade, atau terhapus biasa.
        return back()->with('success', 'Produk berhasil dihapus bersih!');
    }
    // 5. FITUR: LIVE SEARCH ADMIN (Untuk Dropdown Rekomendasi)
    public function searchAdmin(Request $request)
    {
        $query = $request->get('q');
        
        if (strlen($query) < 2) return response()->json([]);

        $products = Product::where('name', 'like', "%{$query}%")
                           ->with(['mainImage', 'category'])
                           ->take(5)
                           ->get()
                           ->map(function ($p) {
                               return [
                                   'id' => $p->id,
                                   'name' => $p->name,
                                   'category' => $p->category->name ?? 'Umum',
                                   'image' => $p->mainImage ? asset('images/' . $p->mainImage->image_path) : asset('images/default.jpg')
                               ];
                           });
        
        return response()->json($products);
    }

    // 6. FITUR: REALTIME TOGGLE STATUS (Aktif/Draft)
    public function toggleStatus(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->is_active = !$product->is_active; // Balikkan status
        $product->save();

        return response()->json([
            'success' => true, 
            'is_active' => $product->is_active,
            'message' => 'Status produk berhasil diubah!'
        ]);
    }

    // 7. TAMPILKAN FORM EDIT PRODUK
    public function edit($id)
    {
        $product = Product::with(['images', 'variants'])->findOrFail($id);
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    // 8. PROSES UPDATE (Logika Super Kompleks: Hapus/Tambah Varian & Gambar)
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required',
            'variants.*.name' => 'required|string',
            'new_images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // A. Update Info Utama
        $product->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . time(),
            'description' => $request->description,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        // B. Sinkronisasi VARIAN
        // Ambil ID varian yang dipertahankan dari form (kalau ada)
        $keptVariantIds = collect($request->variants)->filter(fn($v) => isset($v['id']))->pluck('id')->toArray();
        // Hapus varian yang dibuang oleh Admin di UI
        ProductVariant::where('product_id', $id)->whereNotIn('id', $keptVariantIds)->delete();
        
        // Loop varian dari form: Update yang lama, Create yang baru
        if ($request->has('variants')) {
            foreach ($request->variants as $variant) {
                if (isset($variant['id'])) {
                    ProductVariant::where('id', $variant['id'])->update([
                        'variant_name' => $variant['name'],
                        'price' => $variant['price'],
                        'stock' => $variant['stock'],
                        'weight_gram' => $variant['weight'],
                        'sku' => $variant['sku'] ?? null,
                    ]);
                } else {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'variant_name' => $variant['name'],
                        'price' => $variant['price'],
                        'stock' => $variant['stock'],
                        'weight_gram' => $variant['weight'],
                        'sku' => $variant['sku'] ?? null,
                    ]);
                }
            }
        }

        // C. Sinkronisasi GAMBAR
        $keptImageIds = $request->kept_images ?? [];
        $imagesToDelete = ProductImage::where('product_id', $id)->whereNotIn('id', $keptImageIds)->get();
        foreach ($imagesToDelete as $img) {
            if (file_exists(public_path('images/' . $img->image_path))) {
                unlink(public_path('images/' . $img->image_path));
            }
            $img->delete();
        }

        // Reset semua status Cover (is_main) jadi 0 dulu
        ProductImage::where('product_id', $id)->update(['is_main' => 0]);

        $newUploadedIds = [];
        // Tambah Gambar Baru (Hanya proses jika file-nya benar-benar ada dan valid)
        if ($request->hasFile('new_images')) {
            $existingCount = count($keptImageIds);
            foreach ($request->file('new_images') as $file) {
                if ($file && $file->isValid()) { // <-- INI KUNCI AGAR FILE KOSONG DITOLAK
                    if ($existingCount >= 5) break; 
                    
                    $imageName = time() . '-' . uniqid() . '.' . $file->extension();
                    $file->move(public_path('images'), $imageName);
                    
                    $newImg = ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $imageName,
                        'is_main' => 0, 
                    ]);
                    $newUploadedIds[] = $newImg->id; // Simpan ID gambar yang baru masuk
                    $existingCount++;
                }
            }
        }

        // PENENTUAN COVER (Berdasarkan Titah dari Front-End)
        if ($request->cover_is_new == '1' && count($newUploadedIds) > 0) {
            // Jika Bos menaruh gambar baru di slot pertama
            ProductImage::where('id', $newUploadedIds[0])->update(['is_main' => 1]);
        } else if ($request->cover_old_id) {
            // Jika Bos mempertahankan gambar lama di slot pertama
            ProductImage::where('id', $request->cover_old_id)->update(['is_main' => 1]);
        } else {
            // Fallback (Jaga-jaga jika terjadi keanehan)
            $firstImage = ProductImage::where('product_id', $id)->first();
            if ($firstImage) $firstImage->update(['is_main' => 1]);
        }

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui!');
    }
    public function searchApi(\Illuminate\Http\Request $request)
{
    $q = $request->get('q');
    $products = \App\Models\Product::with(['category', 'mainImage'])
        ->where('name', 'like', "%{$q}%")
        ->where('is_active', 1)
        ->take(6)
        ->get()
        ->map(function ($p) {
            return [
                'id' => $p->id,
                'name' => $p->name,
                'category' => $p->category->name ?? 'Umum',
                'image' => $p->mainImage ? asset('images/' . $p->mainImage->image_path) : asset('images/default.jpg')
            ];
        });

    return response()->json($products);
}
}
