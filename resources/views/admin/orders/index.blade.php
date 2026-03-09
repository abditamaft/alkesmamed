@extends('admin.layouts.app')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-black text-gray-800">Manajemen Pesanan</h1>
    <p class="text-sm text-gray-500 mt-1">Kelola pesanan masuk, resi, dan status pengiriman.</p>
</div>

<div class="flex flex-wrap items-center gap-2 mb-6">
    @php $currentStatus = request('status', 'semua'); @endphp
    
    <a href="{{ route('admin.orders.index', ['status' => 'semua']) }}" class="px-4 py-2 rounded-xl text-sm font-bold transition {{ $currentStatus == 'semua' ? 'bg-slate-800 text-white shadow-md shadow-slate-800/20' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
        Semua Pesanan
    </a>
    
    <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="px-4 py-2 rounded-xl text-sm font-bold transition {{ $currentStatus == 'pending' ? 'bg-yellow-500 text-white shadow-md shadow-yellow-500/20' : 'bg-white text-gray-600 border border-gray-200 hover:bg-yellow-50 hover:text-yellow-600 hover:border-yellow-200' }}">
        Pending
    </a>
    
    <a href="{{ route('admin.orders.index', ['status' => 'paid']) }}" class="px-4 py-2 rounded-xl text-sm font-bold transition {{ $currentStatus == 'paid' ? 'bg-blue-500 text-white shadow-md shadow-blue-500/20' : 'bg-white text-gray-600 border border-gray-200 hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200' }}">
        Sudah Dibayar (Paid)
    </a>
    
    <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" class="px-4 py-2 rounded-xl text-sm font-bold transition {{ $currentStatus == 'processing' ? 'bg-purple-500 text-white shadow-md shadow-purple-500/20' : 'bg-white text-gray-600 border border-gray-200 hover:bg-purple-50 hover:text-purple-600 hover:border-purple-200' }}">
        Diproses
    </a>
    
    <a href="{{ route('admin.orders.index', ['status' => 'shipped']) }}" class="px-4 py-2 rounded-xl text-sm font-bold transition {{ $currentStatus == 'shipped' ? 'bg-indigo-500 text-white shadow-md shadow-indigo-500/20' : 'bg-white text-gray-600 border border-gray-200 hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-200' }}">
        Dikirim
    </a>
    
    <a href="{{ route('admin.orders.index', ['status' => 'completed']) }}" class="px-4 py-2 rounded-xl text-sm font-bold transition {{ $currentStatus == 'completed' ? 'bg-green-500 text-white shadow-md shadow-green-500/20' : 'bg-white text-gray-600 border border-gray-200 hover:bg-green-50 hover:text-green-600 hover:border-green-200' }}">
        Selesai
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                    <th class="px-6 py-4 font-bold">Invoice & Tanggal</th>
                    <th class="px-6 py-4 font-bold">Pelanggan</th>
                    <th class="px-6 py-4 font-bold">Total Nilai</th>
                    <th class="px-6 py-4 font-bold">Status</th>
                    <th class="px-6 py-4 font-bold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @forelse($orders as $order)
                <tr class="border-b border-gray-50 hover:bg-slate-50 transition">
                    <td class="px-6 py-4">
                        <div class="font-bold text-gray-800">{{ $order->invoice_number }}</div>
                        <div class="text-[10px] text-gray-400">{{ $order->created_at->format('d M Y, H:i') }}</div>
                    </td>
                    <td class="px-6 py-4 text-gray-600 font-medium">{{ $order->user->name ?? 'Guest' }}</td>
                    <td class="px-6 py-4 font-bold text-blue-600">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                    <td class="px-6 py-4">
                        @php
                            $color = match($order->status) {
                                'pending' => 'bg-yellow-100 text-yellow-700',
                                'paid' => 'bg-blue-100 text-blue-700',
                                'processing' => 'bg-purple-100 text-purple-700',
                                'shipped' => 'bg-indigo-100 text-indigo-700',
                                'completed' => 'bg-green-100 text-green-700',
                                default => 'bg-red-100 text-red-700',
                            };
                        @endphp
                        <span class="{{ $color }} px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">
                            {{ $order->status }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="inline-flex items-center gap-2 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white px-3 py-1.5 rounded-lg text-xs font-bold transition">
                            <i class="fa-solid fa-eye"></i> Detail / Proses
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-10 text-center text-gray-400 font-medium">Belum ada pesanan masuk.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-gray-50">{{ $orders->links('pagination::tailwind') }}</div>
</div>
@endsection