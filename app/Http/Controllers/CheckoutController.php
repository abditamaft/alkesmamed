<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderShipping;
use App\Models\Cart;
use App\Models\UserAddress;
use App\Models\Province;
use App\Models\City;
use App\Models\ShippingRate;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login untuk checkout.');
        }

        // Tangkap ID produk keranjang yang dicentang dari halaman Cart
        $selectedItems = $request->input('selected_items'); 

        // Kalau tidak ada yang dicentang, kembalikan ke keranjang
        if (empty($selectedItems)) {
            return redirect()->route('cart.index')->with('error', 'Pilih minimal 1 produk untuk di-checkout.');
        }

        // HANYA AMBIL KERANJANG YANG DICENTANG (Tambahkan whereIn)
        $cartItems = Cart::where('user_id', $user->id)
                         ->whereIn('id', $selectedItems)
                         ->with('productVariant.product.mainImage')
                         ->get();

        // 3. Hitung Subtotal
        $subtotal = 0;
        $formattedCartItems = []; // Array untuk mempermudah di Blade
        foreach ($cartItems as $item) {
            $variant = $item->productVariant;
            $product = $variant->product;
            $subtotal += $variant->price * $item->quantity;
            
            $formattedCartItems[] = [
                'name' => $product->name . ' - ' . $variant->variant_name,
                'price' => $variant->price,
                'qty' => $item->quantity,
                'img' => $product->mainImage ? $product->mainImage->image_path : 'default.jpg'
            ];
        }

        // 4. Ambil Alamat Utama User (Untuk Auto-fill)
        $address = UserAddress::where('user_id', $user->id)->where('is_primary', true)->first();
        
        // 5. Ambil Dropdown Provinsi untuk Form
        $provinces = Province::orderBy('name', 'asc')->get();
        $cities = [];
        $shippingCost = 0;

        // 6. Hitung Ongkir Otomatis Jika Alamat Sudah Ada
        if ($address && $address->city_id) {
            $cities = City::where('province_id', $address->province_id)->orderBy('name', 'asc')->get();
            $rate = ShippingRate::where('city_id', $address->city_id)->first();
            $shippingCost = $rate ? $rate->cost : 0;
        }

        $total = $subtotal + $shippingCost;

        return view('checkout', compact('user', 'address', 'provinces', 'cities', 'formattedCartItems', 'subtotal', 'shippingCost', 'total'));
    }

    public function process(Request $request)
    {
        $user = Auth::user();

        // 1. Tambahkan validasi selected_items
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'province_id' => 'required|exists:provinces,id',
            'city_id' => 'required|exists:cities,id',
            'address_line' => 'required|string',
            'payment_method' => 'required|string',
            'selected_items' => 'required|array', // Wajib ada
        ]);

        // 2. HANYA ambil data keranjang yang di-checkout tadi
        $cartItems = Cart::where('user_id', $user->id)
                         ->whereIn('id', $request->selected_items)
                         ->with('productVariant.product')
                         ->get();

        // 3. Hitung Ulang Subtotal & Ongkir
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item->productVariant->price * $item->quantity;
        }

        $rate = ShippingRate::where('city_id', $request->city_id)->first();
        $shippingCost = $rate ? $rate->cost : 0;
        $grandTotal = $subtotal + $shippingCost;

        // 4. Generate Nomor Invoice (Contoh: INV-20260302-ABC12)
        $invoiceNumber = 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(5));

        // Mulai Transaksi Database (Kalau ada 1 tabel gagal, semua dibatalkan otomatis)
        DB::beginTransaction();
        try {
            
            // --- A. SIMPAN KE TABEL ORDERS ---
            $order = Order::create([
                'user_id' => $user->id,
                'invoice_number' => $invoiceNumber,
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'grand_total' => $grandTotal,
                'status' => 'pending', // Status awal: Menunggu Pembayaran
                'payment_method' => $request->payment_method,
                'notes' => $request->notes,
            ]);

            // --- B. SIMPAN KE TABEL ORDER_ITEMS ---
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_variant_id' => $item->product_variant_id,
                    'product_name' => $item->productVariant->product->name,
                    'variant_name' => $item->productVariant->variant_name,
                    'price' => $item->productVariant->price,
                    'quantity' => $item->quantity,
                ]);
            }

            // --- C. SIMPAN KE TABEL ORDER_SHIPPINGS ---
            $city = City::with('province')->find($request->city_id);
            $fullAddress = $request->address_line . ', ' . $city->type . ' ' . $city->name . ', ' . $city->province->name;

            OrderShipping::create([
                'order_id' => $order->id,
                'city_id' => $request->city_id,
                'recipient_name' => $request->name,
                'phone' => $request->phone,
                'full_address' => $fullAddress,
            ]);

            // --- D. CEK CHECKBOX SIMPAN ALAMAT ---
            // Jika user nyentang "Simpan sebagai alamat pengiriman utama di profil saya"
            if ($request->has('update_profile')) {
                UserAddress::updateOrCreate(
                    ['user_id' => $user->id, 'is_primary' => true],
                    [
                        'title' => 'Alamat Utama',
                        'province_id' => $request->province_id,
                        'city_id' => $request->city_id,
                        'address_line' => $request->address_line,
                        'postal_code' => '-', // Default '-' karena form kode pos dihapus
                    ]
                );
            }

            // --- E. KOSONGKAN KERANJANG ---
            Cart::where('user_id', $user->id)->whereIn('id', $request->selected_items)->delete();

            // Selesai! Simpan semua perubahan permanen ke database
            DB::commit();

            // Lempar ke halaman Profil -> Tab Pesanan
            return redirect()->route('profile.index')->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran.');

        } catch (\Exception $e) {
            // Jika ada yang gagal, batalkan semua proses simpan
            DB::rollBack();
            return back()->with('error', 'Gagal memproses pesanan: ' . $e->getMessage());
        }
    }
}