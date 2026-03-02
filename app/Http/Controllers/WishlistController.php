<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wishlist;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    // 1. Fungsi Menampilkan Halaman Wishlist
    public function index(Request $request)
    {
        $userId = Auth::id();

        // Ambil data wishlist beserta relasi produk
        $query = Wishlist::with(['product.mainImage', 'product.variants', 'product.category'])
                         ->where('user_id', $userId);

        // Filter Kategori
        if ($request->filled('kategori')) {
            $query->whereHas('product.category', function($q) use ($request) {
                $q->where('slug', $request->kategori);
            });
        }

        $wishlistItems = $query->paginate(10);

        $categories = Category::whereHas('products.wishlists', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })->get();

        return view('wishlist', compact('wishlistItems', 'categories'));
    }

    // 2. Fungsi Toggle Wishlist (Bisa AJAX Real-time atau Biasa)
    public function store(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);

        $user = Auth::user();
        
        $wishlist = Wishlist::where('user_id', $user->id)
                            ->where('product_id', $request->product_id)
                            ->first();

        if ($wishlist) {
            $wishlist->delete(); // Jika ada, hapus
            $isWishlisted = false;
        } else {
            Wishlist::create([   // Jika belum ada, tambah
                'user_id' => $user->id,
                'product_id' => $request->product_id
            ]);
            $isWishlisted = true;
        }

        $wishlistCount = $user->wishlists()->count();

        // Balasan untuk AJAX (Tanpa Refresh)
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'is_wishlisted' => $isWishlisted,
                'wishlist_count' => $wishlistCount
            ]);
        }

        // Balasan kalau diakses dari form biasa
        return back()->with('success', 'Wishlist diperbarui!');
    }

    // 3. Fungsi Hapus Satuan (INI YANG TADI HILANG BOS!)
    public function destroy($id)
    {
        Wishlist::where('id', $id)->where('user_id', Auth::id())->delete();
        return back()->with('success', 'Produk dihapus dari Favorit!');
    }

    // 4. Fungsi Hapus Massal via Checklist
    public function destroyBulk(Request $request)
    {
        if($request->filled('ids')) {
            Wishlist::whereIn('id', $request->ids)->where('user_id', Auth::id())->delete();
            return back()->with('success', 'Produk terpilih berhasil dihapus!');
        }
        return back()->withErrors(['msg' => 'Pilih minimal satu produk untuk dihapus.']);
    }
}