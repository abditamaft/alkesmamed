<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\ProductVariant;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // 1. Total Pendapatan (Bulan Ini) - Hanya pesanan yang sukses/dibayar
        $revenue = Order::whereMonth('created_at', $currentMonth)
                        ->whereYear('created_at', $currentYear)
                        ->whereIn('status', ['paid', 'processing', 'shipped', 'completed'])
                        ->sum('grand_total');

        // 2. Pesanan Perlu Diproses (Paid / Processing)
        $needProcessing = Order::whereIn('status', ['paid', 'processing'])->count();

        // 3. Total Pelanggan Aktif
        $totalCustomers = User::where('role', 'customer')->count();

        // 4. Produk Stok Tipis (< 5)
        $lowStockCount = ProductVariant::where('stock', '<', 5)->count();

        // 5. Pesanan Masuk Terbaru (Ambil 5 teratas)
        $recentOrders = Order::with('user')->latest()->take(5)->get();

        // 6. Produk Paling Laku (Berdasarkan jumlah terjual di OrderItem)
        $topProducts = OrderItem::select('product_name', DB::raw('SUM(quantity) as total_sold'))
                        ->groupBy('product_name')
                        ->orderByDesc('total_sold')
                        ->take(5)
                        ->get();

        return view('admin.dashboard', compact(
            'revenue', 'needProcessing', 'totalCustomers', 'lowStockCount', 'recentOrders', 'topProducts'
        ));
    }
}