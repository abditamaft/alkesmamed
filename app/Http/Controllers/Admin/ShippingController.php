<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Province;
use App\Models\City;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    // ==========================================
    // BAGIAN PROVINSI
    // ==========================================
    public function index()
    {
        $provinces = Province::withCount('cities')->orderBy('name')->get();
        return view('admin.shipping.index', compact('provinces'));
    }

    public function storeProvince(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255|unique:provinces']);
        Province::create(['name' => $request->name]);
        return back()->with('success', 'Provinsi berhasil ditambahkan!');
    }

    public function updateProvince(Request $request, $id)
    {
        $province = Province::findOrFail($id);
        $request->validate(['name' => 'required|string|max:255|unique:provinces,name,' . $id]);
        $province->update(['name' => $request->name]);
        return back()->with('success', 'Nama Provinsi berhasil diubah!');
    }

    public function destroyProvince($id)
    {
        $province = Province::withCount('cities')->findOrFail($id);
        if ($province->cities_count > 0) {
            return back()->with('error', 'Provinsi tidak bisa dihapus karena masih memiliki kota!');
        }
        $province->delete();
        return back()->with('success', 'Provinsi berhasil dihapus!');
    }

    // ==========================================
    // BAGIAN KOTA & ONGKIR (REVISI PAKAI SHIPPING RATES)
    // ==========================================
    public function showProvince($id)
    {
        // Panggil relasi shippingRate agar tidak error
        $province = Province::with('cities.shippingRate')->findOrFail($id);
        return view('admin.shipping.show', compact('province'));
    }

    public function storeCity(Request $request, $province_id)
    {
        $request->validate([
            'type' => 'required|string',
            'name' => 'required|string|max:255',
            'cost' => 'required|numeric|min:0' // Ubah jadi cost
        ]);

        // 1. Simpan Kota
        $city = City::create([
            'province_id' => $province_id,
            'type' => $request->type,
            'name' => $request->name,
        ]);

        // 2. Simpan Ongkir ke tabel shipping_rates
        \App\Models\ShippingRate::create([
            'city_id' => $city->id,
            'cost' => $request->cost
        ]);

        return back()->with('success', 'Kota dan Ongkir berhasil ditambahkan!');
    }

    public function updateCity(Request $request, $id)
    {
        $city = City::findOrFail($id);
        $request->validate([
            'type' => 'required|string',
            'name' => 'required|string|max:255',
            'cost' => 'required|numeric|min:0' // Ubah jadi cost
        ]);

        // 1. Update nama kota
        $city->update([
            'type' => $request->type,
            'name' => $request->name,
        ]);

        // 2. Update atau Buat Ongkir baru di shipping_rates
        \App\Models\ShippingRate::updateOrCreate(
            ['city_id' => $city->id],
            ['cost' => $request->cost]
        );

        return back()->with('success', 'Data Kota & Ongkir berhasil diperbarui!');
    }

    public function destroyCity($id)
    {
        // Hapus kota (otomatis hapus shipping_rates jika pakai onDelete Cascade di DB, tapi kita hapus manual untuk aman)
        $city = City::findOrFail($id);
        if($city->shippingRate) {
            $city->shippingRate->delete();
        }
        $city->delete();
        
        return back()->with('success', 'Kota beserta ongkirnya berhasil dihapus!');
    }
    // FITUR: LIVE SEARCH KOTA & PROVINSI (AJAX)
    public function search(Request $request)
    {
        $q = $request->get('q');
        
        // Cari berdasarkan nama Kota ATAU nama Provinsi
        $cities = City::with('province')
            ->where('name', 'like', "%{$q}%")
            ->orWhereHas('province', function($query) use ($q) {
                $query->where('name', 'like', "%{$q}%");
            })
            ->take(10) // Ambil 10 rekomendasi teratas agar tidak berat
            ->get();
            
        return response()->json($cities);
    }
}