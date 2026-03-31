<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Province;
use App\Models\City;
use App\Models\Order;
use App\Models\Cart;
use App\Models\UserAddress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Ambil alamat utama user (jika ada)
        $address = UserAddress::where('user_id', $user->id)->where('is_primary', true)->first();
        
        // Ambil semua provinsi untuk dropdown
        $provinces = Province::orderBy('name', 'asc')->get();
        
        // Ambil kota dari provinsi yang sudah dipilih user (jika ada)
        $cities = [];
        if ($address && $address->province_id) {
            $cities = City::where('province_id', $address->province_id)->orderBy('name', 'asc')->get();
        }

        // Tambahkan with(...) untuk menyedot detail pesanan & gambar produknya sekalian!
        $unpaidOrders = Order::with(['orderItems.productVariant.product.mainImage'])
                            ->where('user_id', $user->id)
                            ->where('status', 'pending')
                            ->orderBy('created_at', 'desc')->get();
                            
        $activeOrders = Order::with(['orderItems.productVariant.product.mainImage'])
                            ->where('user_id', $user->id)
                            ->whereIn('status', ['paid', 'processing', 'shipped'])
                            ->orderBy('created_at', 'desc')->get();

        return view('profile.index', compact('user', 'address', 'provinces', 'cities', 'unpaidOrders', 'activeOrders'));
    }

    public function getCities($province_id)
    {
        // Fungsi ini dipanggil oleh JavaScript (AJAX) saat ganti provinsi
        $cities = City::where('province_id', $province_id)->orderBy('name', 'asc')->get();
        return response()->json($cities);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|numeric|digits_between:10,13',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'province_id' => 'required|exists:provinces,id',
            'city_id' => 'required|exists:cities,id',
            'address_line' => 'required|string',
        ], [
            // Kumpulan Pesan Error Kustom Bahasa Indonesia
            'profile_picture.max' => 'Gagal: Ukuran foto profil terlalu besar. Maksimal hanya 2 MB!',
            'profile_picture.image' => 'Gagal: File yang diunggah harus berupa gambar.',
            'profile_picture.mimes' => 'Gagal: Format foto harus berupa JPG, JPEG, atau PNG.',
            
            'name.required' => 'Nama lengkap wajib diisi.',
            'phone.required' => 'Nomor telepon WhatsApp wajib diisi.',
            'phone.numeric' => 'Gagal: Nomor telepon hanya boleh berisi angka.',
            'phone.digits_between' => 'Gagal: Nomor telepon harus terdiri dari 10 hingga 13 angka.',
            'province_id.required' => 'Silakan pilih Provinsi terlebih dahulu.',
            'city_id.required' => 'Silakan pilih Kota/Kabupaten terlebih dahulu.',
            'address_line.required' => 'Alamat lengkap tidak boleh kosong.',
        ]);

        // Update Data User
        $user->name = $request->name;
        $user->phone = $request->phone;

        // Handle Upload Foto Profil
        if ($request->hasFile('profile_picture')) {
            // Hapus foto lama di folder public/profiles jika ada
            if ($user->profile_picture && file_exists(public_path('profiles/' . $user->profile_picture))) {
                unlink(public_path('profiles/' . $user->profile_picture));
            }
            
            $fileName = time() . '_' . $request->file('profile_picture')->getClientOriginalName();
            
            // Pindahkan file langsung ke folder public/profiles (Bebas dari masalah Symlink!)
            $request->file('profile_picture')->move(public_path('profiles'), $fileName);
            
            $user->profile_picture = $fileName;
        }
        $user->save();

        // Update atau Buat Alamat Baru
        UserAddress::updateOrCreate(
            ['user_id' => $user->id, 'is_primary' => true],
            [
                'title' => 'Alamat Utama',
                'province_id' => $request->province_id,
                'city_id' => $request->city_id,
                'address_line' => $request->address_line,
                'postal_code' => $request->postal_code ?? '-', // Jika tidak ada, isi '-'
            ]
        );

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }
    public function cancelOrder($id)
    {
        // Cari pesanan berdasarkan ID beserta itemnya
        $order = Order::with('orderItems')->findOrFail($id);

        // 1. Validasi Keamanan: Pastikan ini pesanan miliknya sendiri
        if ($order->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        // 2. Validasi Status: Hanya pesanan 'pending' yang boleh dibatalkan
        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'Pesanan tidak dapat dibatalkan karena sudah diproses.');
        }

        // Mulai Transaksi Database
        DB::beginTransaction();
        try {
            // 3. Ubah status pesanan menjadi canceled
            $order->status = 'cancelled'; 
            $order->save();

            // 4. MAHZAB B: Kembalikan semua barang ke dalam Keranjang
            foreach ($order->orderItems as $item) {
                // Cek apakah barang ini kebetulan sudah ada di keranjang sekarang
                $existingCart = Cart::where('user_id', Auth::id())
                                    ->where('product_variant_id', $item->product_variant_id)
                                    ->first();

                if ($existingCart) {
                    // Kalau barangnya sudah ada, cukup tambahkan jumlahnya (quantity)
                    $existingCart->quantity += $item->quantity;
                    $existingCart->save();
                } else {
                    // Kalau belum ada, buat data keranjang baru
                    Cart::create([
                        'user_id' => Auth::id(),
                        'product_variant_id' => $item->product_variant_id,
                        'quantity' => $item->quantity,
                    ]);
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Pesanan berhasil dibatalkan. Barang telah dikembalikan ke Keranjang Anda!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal membatalkan pesanan: ' . $e->getMessage());
        }
    }
}
