@extends('admin.layouts.app')

@section('content')
<div class="mb-8">
    <a href="{{ route('admin.orders.index') }}" class="text-sm font-bold text-gray-500 hover:text-blue-600 transition flex items-center gap-2 mb-4">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Pesanan
    </a>
    <h1 class="text-2xl font-black text-gray-800">Detail Pesanan: #{{ $order->invoice_number }}</h1>
</div>

@if(session('success'))
    <div class="bg-green-50 text-green-700 px-4 py-3 rounded-xl text-sm font-bold mb-6 flex items-center gap-2 border border-green-200"><i class="fa-solid fa-check-circle"></i> {{ session('success') }}</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <div class="lg:col-span-2 space-y-6">
        
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h2 class="text-lg font-bold text-gray-800 mb-4 border-b border-gray-100 pb-3">Informasi Pengiriman</h2>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="block text-gray-500 mb-1 text-xs">Penerima:</span>
                    <span class="font-bold text-gray-800">{{ $order->shipping->recipient_name ?? '-' }}</span>
                </div>
                <div>
                    <span class="block text-gray-500 mb-1 text-xs">No. HP:</span>
                    <span class="font-bold text-gray-800">{{ $order->shipping->phone ?? '-' }}</span>
                </div>
                <div class="col-span-2">
                    <span class="block text-gray-500 mb-1 text-xs">Alamat Lengkap:</span>
                    <span class="text-gray-800 font-medium">{{ $order->shipping->full_address ?? '-' }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h2 class="text-lg font-bold text-gray-800 mb-4 border-b border-gray-100 pb-3">Produk yang Dibeli</h2>
            <div class="space-y-4">
                @foreach($order->items as $item)
                <div class="flex justify-between items-center bg-slate-50 p-3 rounded-xl border border-gray-100">
                    <div>
                        <h4 class="font-bold text-gray-800 text-sm">{{ $item->product_name }}</h4>
                        <p class="text-[11px] text-gray-500 font-medium">Varian: {{ $item->variant_name }}</p>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-bold text-gray-800">{{ $item->quantity }}x Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                        <div class="text-xs font-black text-blue-600">Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}</div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="mt-6 border-t border-gray-100 pt-4 space-y-2">
                <div class="flex justify-between text-sm"><span class="text-gray-500">Subtotal Produk</span><span class="font-bold">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span></div>
                <div class="flex justify-between text-sm"><span class="text-gray-500">Ongkos Kirim</span><span class="font-bold">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span></div>
                <div class="flex justify-between text-lg mt-2 pt-2 border-t border-dashed border-gray-200"><span class="font-black text-gray-800">Grand Total</span><span class="font-black text-blue-600">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span></div>
            </div>
        </div>
    </div>

    <div class="lg:col-span-1">
        <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 sticky top-6">
            @csrf @method('PUT')
            
            <h2 class="text-lg font-bold text-gray-800 mb-4 border-b border-gray-100 pb-3"><i class="fa-solid fa-truck-fast text-blue-500 mr-2"></i> Eksekusi Pesanan</h2>

            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-600 mb-1.5">Ubah Status</label>
                <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-bold outline-none focus:border-blue-500 focus:ring-1">
                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Tertunda (Pending)</option>
                    <option value="paid" {{ $order->status == 'paid' ? 'selected' : '' }}>Sudah Dibayar (Paid)</option>
                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Sedang Diproses (Processing)</option>
                    <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Dikirim (Shipped)</option>
                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Selesai (Completed)</option>
                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan (Cancelled)</option>
                </select>
                <p class="text-[10px] text-gray-400 mt-1">Mengubah status menjadi "Shipped" atau "Completed" akan mengurangi angka badge di sidebar.</p>
            </div>

            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-600 mb-1.5">Nama Kurir Ekspedisi</label>
                <input type="text" name="courier_name" value="{{ $order->shipping->courier_name ?? '' }}" placeholder="JNE / J&T / Sicepat..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-1">
            </div>

            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-600 mb-1.5">Nomor Resi (Tracking Number)</label>
                <input type="text" name="tracking_number" value="{{ $order->shipping->tracking_number ?? '' }}" placeholder="Masukkan resi pengiriman..." class="w-full bg-white border border-gray-300 rounded-xl px-4 py-2.5 text-sm font-bold text-blue-600 outline-none focus:border-blue-500 focus:ring-1 tracking-wider uppercase">
            </div>

            <div class="mb-6">
                <label class="block text-xs font-bold text-gray-600 mb-1.5">Catatan Tambahan (Internal / Resi)</label>
                <textarea name="notes" rows="3" placeholder="Contoh: Paket di-pickup jam 14:00..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-1">{{ $order->notes ?? '' }}</textarea>
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-3 rounded-xl shadow-lg shadow-blue-500/30 transition transform hover:-translate-y-1">
                <i class="fa-solid fa-save mr-2"></i> Simpan Perubahan
            </button>
        </form>
    </div>
</div>
@endsection