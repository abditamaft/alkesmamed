<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Menampilkan Halaman Keranjang (Paginasi max 10)
    public function index()
    {
        $cartItems = Cart::with(['variant.product.mainImage'])
                         ->where('user_id', Auth::id())
                         ->paginate(10);
                         
        return view('keranjang', compact('cartItems'));
    }

    // Menambah ke Keranjang (Real-time AJAX)
    public function store(Request $request)
    {
        $request->validate([
            'product_variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $user = Auth::user();
        
        $cart = Cart::where('user_id', $user->id)
                    ->where('product_variant_id', $request->product_variant_id)
                    ->first();

        if ($cart) {
            $cart->update(['quantity' => $cart->quantity + $request->quantity]);
        } else {
            Cart::create([
                'user_id' => $user->id,
                'product_variant_id' => $request->product_variant_id,
                'quantity' => $request->quantity
            ]);
        }

        // --- BAGIAN AJAIB AJAX ---
        if ($request->wantsJson() || $request->ajax()) {
            $cartCount = $user->carts()->sum('quantity');
            
            // Ambil data terbaru untuk dioper ke Mini-Cart Header
            $miniCartCarts = $user->carts()->with('variant.product.mainImage')->get();
            $miniCartData = $miniCartCarts->map(function($c) {
                return [
                    'id' => $c->id,
                    'name' => $c->variant->product->name,
                    'variant_name' => $c->variant->variant_name,
                    'price' => $c->variant->price,
                    'qty' => $c->quantity,
                    'img' => $c->variant->product->mainImage ? asset('images/' . $c->variant->product->mainImage->image_path) : asset('images/default.jpg')
                ];
            });

            return response()->json([
                'success' => true, 
                'cart_count' => $cartCount,
                'cart_items' => $miniCartData
            ]);
        }
        
        return back()->with('success', 'Produk ditambahkan ke keranjang!');
    }

    // Mengupdate Quantity Masal (Tombol "Update Keranjang")
    public function updateBulk(Request $request)
    {
        if($request->filled('items')) {
            foreach($request->items as $id => $qty) {
                Cart::where('id', $id)->where('user_id', Auth::id())->update(['quantity' => $qty]);
            }
            return back()->with('success', 'Keranjang berhasil diperbarui!');
        }
        return back();
    }

    // Hapus Satuan
    public function destroy($id)
    {
        Cart::where('id', $id)->where('user_id', Auth::id())->delete();
        return back()->with('success', 'Produk dihapus dari keranjang!');
    }

    // Hapus Massal via Checkbox
    public function destroyBulk(Request $request)
    {
        if($request->filled('ids')) {
            Cart::whereIn('id', $request->ids)->where('user_id', Auth::id())->delete();
            return back()->with('success', 'Produk terpilih berhasil dihapus!');
        }
        return back()->withErrors(['msg' => 'Pilih minimal satu produk untuk dihapus.']);
    }
}