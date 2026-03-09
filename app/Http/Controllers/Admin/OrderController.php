<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // 1. FITUR AJAX: Ambil Jumlah Pesanan yang Butuh Perhatian
    public function badgeCount()
    {
        // Hitung pesanan yang statusnya masih pending, paid, atau processing
        $count = Order::whereIn('status', ['pending', 'paid', 'processing'])->count();
        return response()->json(['count' => $count]);
    }

    // 2. DAFTAR SEMUA PESANAN & FILTER STATUS
    public function index(Request $request)
    {
        // Siapkan query dasar
        $query = Order::with(['user', 'shipping'])->latest();

        // Jika Bos memilih filter status (dan statusnya bukan 'semua')
        if ($request->has('status') && $request->status != 'semua') {
            $query->where('status', $request->status);
        }

        // Gunakan withQueryString() agar saat Bos klik halaman 2, filter statusnya tidak hilang!
        $orders = $query->paginate(15)->withQueryString();
        
        return view('admin.orders.index', compact('orders'));
    }

    // 3. DETAIL & FORM UPDATE PESANAN
    public function show($id)
    {
        $order = Order::with(['user', 'items', 'shipping.city.province'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    // 4. PROSES UPDATE (Status, Resi, Kurir, Catatan)
    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        // Update tabel orders (Status & Notes)
        $order->update([
            'status' => $request->status,
            'notes'  => $request->notes,
        ]);

        // Update tabel order_shippings (Kurir & No Resi)
        if ($order->shipping) {
            $order->shipping->update([
                'courier_name'    => $request->courier_name,
                'tracking_number' => $request->tracking_number,
            ]);
        }

        return back()->with('success', 'Data pesanan & pengiriman berhasil diperbarui!');
    }
}