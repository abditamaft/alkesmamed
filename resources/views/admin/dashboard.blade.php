@extends('admin.layouts.app')

@section('content')
<div class="mb-8 flex justify-between items-end">
    <div>
        <h1 class="text-3xl font-black text-gray-800 tracking-tight mb-1">Dashboard</h1>
        <p class="text-sm text-gray-500 font-medium">Ringkasan aktivitas toko Alkes Mamed hari ini.</p>
    </div>
    <button class="bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-lg text-sm font-bold shadow-sm transition flex items-center gap-2">
        <i class="fa-solid fa-download"></i> Download Laporan
    </button>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between hover:shadow-md transition">
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Pendapatan (Bulan Ini)</p>
                <h3 class="text-2xl font-black text-gray-800">Rp {{ number_format($revenue, 0, ',', '.') }}</h3>
            </div>
            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-500 text-lg">
                <i class="fa-solid fa-wallet"></i>
            </div>
        </div>
        <p class="text-xs font-bold text-green-500"><i class="fa-solid fa-arrow-trend-up"></i> Update Real-time</p>
    </div>

    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between hover:shadow-md transition">
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Pesanan Perlu Diproses</p>
                <h3 class="text-2xl font-black text-gray-800">{{ $needProcessing }}</h3>
            </div>
            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-500 text-lg">
                <i class="fa-solid fa-box"></i>
            </div>
        </div>
        @if($needProcessing > 0)
            <p class="text-xs font-bold text-red-500">Segera proses pengiriman!</p>
        @else
            <p class="text-xs font-bold text-gray-400">Semua pesanan sudah diproses.</p>
        @endif
    </div>

    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between hover:shadow-md transition">
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Total Pelanggan</p>
                <h3 class="text-2xl font-black text-gray-800">{{ number_format($totalCustomers, 0, ',', '.') }}</h3>
            </div>
            <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center text-purple-500 text-lg">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>
        <p class="text-xs font-medium text-gray-400">Terdaftar di sistem</p>
    </div>

    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between hover:shadow-md transition">
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Produk Stok Tipis</p>
                <h3 class="text-2xl font-black text-gray-800">{{ $lowStockCount }}</h3>
            </div>
            <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center text-orange-500 text-lg">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
        </div>
        @if($lowStockCount > 0)
            <p class="text-xs font-bold text-orange-500">Kurang dari 5 item</p>
        @else
            <p class="text-xs font-bold text-gray-400">Stok produk aman.</p>
        @endif
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <div class="lg:col-span-2 bg-white border border-gray-100 rounded-2xl shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-bold text-gray-800">Pesanan Masuk Terbaru</h3>
            <a href="{{ route('admin.orders.index') }}" class="text-sm font-bold text-blue-600 hover:text-blue-800 transition">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] font-bold text-gray-400 uppercase tracking-wider border-b border-gray-100">
                        <th class="pb-3">Invoice</th>
                        <th class="pb-3">Pelanggan</th>
                        <th class="pb-3">Total</th>
                        <th class="pb-3 text-center">Status</th>
                        <th class="pb-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse($recentOrders as $order)
                    <tr class="border-b border-gray-50 hover:bg-slate-50 transition">
                        <td class="py-4 font-bold text-blue-600">
                            <a href="{{ route('admin.orders.show', $order->id) }}">{{ $order->invoice_number }}</a>
                        </td>
                        <td class="py-4 text-gray-700 font-medium">{{ $order->user->name ?? 'Guest' }}</td>
                        <td class="py-4 font-black text-gray-800">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                        <td class="py-4 text-center">
                            @php
                                $statusStyle = match($order->status) {
                                    'pending' => 'bg-gray-100 text-gray-600',
                                    'paid', 'processing' => 'bg-yellow-100 text-yellow-700',
                                    'shipped' => 'bg-blue-100 text-blue-700',
                                    'completed' => 'bg-green-100 text-green-700',
                                    default => 'bg-red-100 text-red-700',
                                };
                                $statusText = match($order->status) {
                                    'paid', 'processing' => 'Perlu Dikirim',
                                    'completed' => 'Selesai',
                                    default => ucfirst($order->status),
                                };
                            @endphp
                            <span class="{{ $statusStyle }} text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wide">{{ $statusText }}</span>
                        </td>
                        <td class="py-4 text-right">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white px-3 py-1.5 rounded-lg text-xs font-bold transition">Proses</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="py-6 text-center text-gray-400">Belum ada pesanan terbaru.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-6">Produk Paling Laku</h3>
        <div class="space-y-4">
            @forelse($topProducts as $item)
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-slate-50 border border-gray-100 flex items-center justify-center text-blue-500">
                    <i class="fa-solid fa-box-open"></i>
                </div>
                <div class="flex-1">
                    <h4 class="text-sm font-bold text-gray-800 line-clamp-1">{{ $item->product_name }}</h4>
                    <p class="text-xs text-gray-500 mt-0.5">Terjual: <span class="font-bold">{{ $item->total_sold }} pcs</span></p>
                </div>
            </div>
            @empty
            <p class="text-center text-sm text-gray-400">Belum ada data penjualan.</p>
            @endforelse
        </div>
    </div>

</div>
@endsection