@extends('layouts.app')

@section('content')
<div class="bg-white min-h-screen py-10 px-6">

    <nav class="text-sm text-gray-400 mb-10 max-w-7xl mx-auto">
        <a href="/" class="hover:text-blue-600 transition">Beranda</a> / 
        <a href="/keranjang" class="hover:text-blue-600 transition">Keranjang</a> / 
        <span class="text-gray-800 font-medium">Checkout</span>
    </nav>

    <div class="max-w-7xl mx-auto">
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-xl mb-6 font-bold shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-xl mb-6 shadow-sm">
                <p class="font-bold mb-2">Ada isian yang belum lengkap/salah:</p>
                <ul class="list-disc pl-5 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <div class="mb-10 space-y-4">
            <div class="bg-[#f8f8f8] border-t-2 border-blue-500 p-4 text-sm text-gray-600 flex gap-2 items-center rounded-b-md">
                <i class="fa-solid fa-tag"></i>
                Punya kupon? <button class="text-blue-500 hover:underline">Klik di sini untuk memasukkan kode Anda</button>
            </div>
        </div>

        <form action="{{ route('checkout.process') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            @csrf
            
            @php
                $itemsToCheckout = old('selected_items', request('selected_items', []));
            @endphp
            @foreach($itemsToCheckout as $itemId)
                <input type="hidden" name="selected_items[]" value="{{ $itemId }}">
            @endforeach
            <div class="lg:col-span-2 space-y-8 page-enter" style="animation-delay: 100ms;">
                <h2 class="text-2xl font-bold text-gray-900 border-b border-gray-100 pb-4">Detail Pengiriman</h2>
                
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nama Lengkap *</label>
                    <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" class="w-full bg-[#f8f8f8] border-0 rounded-full px-6 py-3 focus:ring-1 focus:ring-blue-500 outline-none transition" required>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nomor Telepon *</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone ?? '') }}" class="w-full bg-[#f8f8f8] border-0 rounded-full px-6 py-3 focus:ring-1 focus:ring-blue-500 outline-none transition" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Alamat Email *</label>
                        <input type="email" name="email" value="{{ $user->email ?? '' }}" class="w-full bg-gray-100 text-gray-500 border-0 rounded-full px-6 py-3 focus:outline-none transition cursor-not-allowed" readonly>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-gray-100">
                    <div class="w-full relative">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Provinsi *</label>
                        <div class="relative w-full">
                            <select name="province_id" id="province_id" class="w-full bg-[#f8f8f8] border-0 rounded-full pl-6 pr-12 py-3 text-sm focus:ring-1 focus:ring-blue-500 outline-none transition appearance-none truncate cursor-pointer" required>
                                <option value="">Pilih Provinsi</option>
                                @foreach($provinces as $prov)
                                    <option value="{{ $prov->id }}" {{ old('province_id', $address->province_id ?? '') == $prov->id ? 'selected' : '' }}>
                                        {{ $prov->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-5 text-gray-400">
                                <i class="fa-solid fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="w-full relative">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Kota/Kabupaten *</label>
                        <div class="relative w-full">
                            <select name="city_id" id="city_id" class="w-full bg-[#f8f8f8] border-0 rounded-full pl-6 pr-12 py-3 text-sm focus:ring-1 focus:ring-blue-500 outline-none transition appearance-none truncate cursor-pointer" required>
                                <option value="">Pilih Kota/Kabupaten</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}" {{ old('city_id', $address->city_id ?? '') == $city->id ? 'selected' : '' }}>
                                        {{ $city->type }} {{ $city->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-5 text-gray-400">
                                <i class="fa-solid fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Alamat Lengkap *</label>
                    <textarea name="address_line" rows="3" placeholder="Jalan, No. Rumah, RT/RW, Kecamatan" class="w-full bg-[#f8f8f8] border-0 rounded-2xl px-6 py-3 focus:ring-1 focus:ring-blue-500 outline-none transition resize-none" required>{{ old('address_line', $address->address_line ?? '') }}</textarea>
                </div>

                <div class="pt-2">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="update_profile" value="1" class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500" {{ old('update_profile') ? 'checked' : '' }}>
                        <span class="text-gray-600 text-sm">Simpan sebagai alamat pengiriman utama di profil saya</span>
                    </label>
                </div>

                <div class="pt-4 border-t border-gray-100">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Catatan Pesanan (Opsional)</label>
                    <textarea name="notes" rows="4" placeholder="Catatan khusus untuk pengiriman atau produk." class="w-full bg-[#f8f8f8] border-0 rounded-2xl px-6 py-3 focus:ring-1 focus:ring-blue-500 outline-none transition resize-none">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="bg-[#f9f9f9] p-8 rounded-xl h-fit border border-gray-100 page-enter" style="animation-delay: 300ms;">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Ringkasan Pesanan</h3>

                <div class="space-y-4 mb-6 border-b border-gray-200 pb-6">
                    @forelse($formattedCartItems as $item)
                    <div class="flex justify-between items-center gap-4">
                        <div class="flex items-center gap-3 flex-1 min-w-0">
                            <div class="w-12 h-12 bg-white rounded-md border border-gray-200 p-1 flex-shrink-0">
                                <img src="{{ asset('images/' . $item['img']) }}" class="w-full h-full object-contain">
                            </div>
                            <div class="text-sm min-w-0 flex-1">
                                <p class="font-bold text-gray-800 truncate" title="{{ $item['name'] }}">{{ $item['name'] }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">x {{ $item['qty'] }}</p>
                            </div>
                        </div>
                        <span class="font-bold text-gray-700 whitespace-nowrap flex-shrink-0 text-right">
                            Rp {{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}
                        </span>
                    </div>
                    @empty
                    <p class="text-sm text-red-500">Keranjang kosong!</p>
                    @endforelse
                </div>

                <div class="space-y-3 border-b border-gray-200 pb-6 mb-6">
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Subtotal</span>
                        <span class="font-bold text-gray-800">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Pengiriman</span>
                        <span class="font-bold text-gray-800" id="shipping-cost-display">Rp {{ number_format($shippingCost, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-lg font-bold text-gray-900 pt-2">
                        <span>Total</span>
                        <span class="text-blue-600" id="grand-total-display">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="space-y-4 mb-8" x-data="{ payment: '{{ old('payment_method', 'transfer') }}' }">
                    <div class="border border-gray-200 rounded-lg overflow-hidden bg-white">
                        <div class="flex items-center p-4 cursor-pointer" @click="payment = 'transfer'" :class="payment === 'transfer' ? 'bg-blue-50' : ''">
                            <input type="radio" name="payment_method" value="transfer" x-model="payment" class="mr-3 text-blue-600 focus:ring-blue-500">
                            <span class="font-bold text-sm text-gray-800">Transfer Bank / E-Wallet (Midtrans)</span>
                        </div>
                        <div x-show="payment === 'transfer'" class="p-4 text-xs text-gray-500 bg-blue-50 border-t border-blue-100">
                            Pembayaran otomatis terverifikasi via Midtrans. Mendukung BCA, BNI, GoPay, QRIS, dll.
                        </div>
                    </div>

                    <div class="border border-gray-200 rounded-lg overflow-hidden bg-white">
                        <div class="flex items-center p-4 cursor-pointer" @click="payment = 'cod'" :class="payment === 'cod' ? 'bg-blue-50' : ''">
                            <input type="radio" name="payment_method" value="cod" x-model="payment" class="mr-3 text-blue-600 focus:ring-blue-500">
                            <span class="font-bold text-sm text-gray-800">Bayar di Tempat (COD)</span>
                        </div>
                        <div x-show="payment === 'cod'" style="display: none;" class="p-4 text-xs text-gray-500 bg-blue-50 border-t border-blue-100">
                            Bayar tunai kepada kurir saat barang sampai di alamat Anda.
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full bg-blue-500 text-white font-bold py-4 rounded-full hover:bg-blue-600 transition shadow-lg shadow-blue-500/30">
                    Buat Pesanan Sekarang
                </button>
            </div>

        </form>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const provinceSelect = document.getElementById('province_id');
        const citySelect = document.getElementById('city_id');

        provinceSelect.addEventListener('change', function() {
            const provinceId = this.value;
            citySelect.innerHTML = '<option value="">Memuat data...</option>';
            
            if(provinceId) {
                fetch(`/api/cities/${provinceId}`)
                    .then(response => response.json())
                    .then(data => {
                        citySelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
                        data.forEach(city => {
                            const option = document.createElement('option');
                            option.value = city.id;
                            option.textContent = city.type + ' ' + city.name;
                            citySelect.appendChild(option);
                        });
                    });
            } else {
                citySelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
            }
        });
    });
</script>
@endsection