@extends('layouts.app')

@section('content')
<div class="bg-[#f8f8f8] min-h-screen py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" x-data="{ activeTab: 'profil' }">
        
        <nav class="text-sm text-gray-400 mb-8 page-enter" style="animation-delay: 50ms;">
            <a href="/" class="hover:text-blue-500 transition">Beranda</a> / 
            <span class="text-gray-800 font-medium">Akun Saya</span>
        </nav>

        <h1 class="text-3xl font-bold text-gray-900 mb-10 page-enter" style="animation-delay: 100ms;">Akun Saya</h1>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl relative mb-8 page-enter">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex flex-col md:flex-row gap-10 page-enter" style="animation-delay: 200ms;">
            
            <div class="w-full md:w-1/4">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-8">
                    <button @click="activeTab = 'profil'" :class="activeTab === 'profil' ? 'bg-blue-500 text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-blue-500'" class="w-full text-left px-8 py-5 font-bold transition-colors border-b border-gray-100 text-sm tracking-wide">
                        Profil Saya
                    </button>
                    <button @click="activeTab = 'pesanan'" :class="activeTab === 'pesanan' ? 'bg-blue-500 text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-blue-500'" class="w-full text-left px-8 py-5 font-bold transition-colors border-b border-gray-100 text-sm tracking-wide flex justify-between items-center">
                        Pesanan Saya
                        @if($unpaidOrders->count() > 0)
                            <span class="bg-red-500 text-white text-[10px] px-2.5 py-1 rounded-full">{{ $unpaidOrders->count() }}</span>
                        @endif
                    </button>
                    <button @click="activeTab = 'favorit'" :class="activeTab === 'favorit' ? 'bg-blue-500 text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-blue-500'" class="w-full text-left px-8 py-5 font-bold transition-colors border-b border-gray-100 text-sm tracking-wide">
                        Favorit Saya
                    </button>
                    <form method="POST" action="{{ route('logout') }}" class="block">
                        @csrf
                        <button type="submit" class="w-full text-left px-8 py-5 font-bold text-gray-600 hover:bg-red-50 hover:text-red-500 transition-colors text-sm tracking-wide">
                            Keluar
                        </button>
                    </form>
                </div>
            </div>

            <div class="w-full md:w-3/4">
                
                <div x-show="activeTab === 'profil'" x-transition.opacity.duration.300ms>
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        @if ($errors->any())
                            <div class="bg-red-100 border-2 border-red-500 text-red-700 px-6 py-4 rounded-xl mb-8 font-bold shadow-sm">
                                <p class="mb-2 text-lg">Gagal Menyimpan! Cek kesalahan berikut:</p>
                                <ul class="list-disc pl-5 text-sm font-normal">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        <div class="bg-white p-10 rounded-2xl shadow-sm border border-gray-100">
                            <h2 class="text-xl font-bold border-b border-gray-100 pb-5 mb-8 text-gray-800">Foto Profil</h2>
                            <div class="flex flex-col items-center justify-center space-y-6">
                                <div class="w-36 h-36 rounded-full border-4 border-[#f8f8f8] overflow-hidden bg-gray-50 flex items-center justify-center shadow-inner">
                                    <img id="preview-image" src="{{ $user->profile_picture ? asset('profiles/'.$user->profile_picture) : asset('images/default-avatar.png') }}" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <label class="cursor-pointer bg-[#f8f8f8] hover:bg-gray-200 text-gray-700 px-6 py-3 rounded-full font-bold transition text-sm border border-gray-200 shadow-sm">
                                        Pilih Gambar Baru
                                        <input type="file" name="profile_picture" id="profile_picture" class="hidden" accept="image/*" onchange="previewFile(this)">
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white p-10 rounded-2xl shadow-sm border border-gray-100">
                            <h2 class="text-xl font-bold border-b border-gray-100 pb-5 mb-8 text-gray-800">Informasi Akun & Alamat</h2>
                            
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Email</label>
                                    <input type="email" value="{{ $user->email }}" class="w-full bg-gray-100 border-0 rounded-full px-6 py-4 text-gray-500 cursor-not-allowed" readonly>
                                    <span class="text-xs text-gray-400 mt-2 block pl-4">Email tidak dapat diubah.</span>
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nama Lengkap *</label>
                                    <input type="text" name="name" value="{{ $user->name }}" class="w-full bg-white border border-gray-200 rounded-full px-6 py-4 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition shadow-sm" required>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">No. Telepon (WhatsApp) *</label>
                                    <input type="text" name="phone" value="{{ $user->phone }}" class="w-full bg-white border border-gray-200 rounded-full px-6 py-4 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition shadow-sm" required>
                                </div>

                                <div class="pt-6 mt-6 border-t border-gray-100">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6 mb-6 w-full max-w-full overflow-hidden">
                                    
                                    <div class="w-full relative">
                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Provinsi *</label>
                                        <div class="relative w-full">
                                            <select name="province_id" id="province_id" class="w-full bg-white border border-gray-200 rounded-full pl-5 md:pl-6 pr-10 md:pr-12 py-3 md:py-4 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition shadow-sm appearance-none truncate cursor-pointer" required>
                                                <option value="">Pilih Provinsi</option>
                                                @foreach($provinces as $prov)
                                                    <option value="{{ $prov->id }}" {{ ($address->province_id ?? '') == $prov->id ? 'selected' : '' }}>
                                                        {{ $prov->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4 md:pr-5 text-gray-400">
                                                <i class="fa-solid fa-chevron-down text-[10px] md:text-xs"></i>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="w-full relative">
                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Kota/Kabupaten *</label>
                                        <div class="relative w-full">
                                            <select name="city_id" id="city_id" class="w-full bg-white border border-gray-200 rounded-full pl-5 md:pl-6 pr-10 md:pr-12 py-3 md:py-4 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition shadow-sm appearance-none truncate cursor-pointer" required>
                                                <option value="">Pilih Kota/Kabupaten</option>
                                                @foreach($cities as $city)
                                                    <option value="{{ $city->id }}" {{ ($address->city_id ?? '') == $city->id ? 'selected' : '' }}>
                                                        {{ $city->type }} {{ $city->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4 md:pr-5 text-gray-400">
                                                <i class="fa-solid fa-chevron-down text-[10px] md:text-xs"></i>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Alamat Lengkap *</label>
                                        <textarea name="address_line" rows="3" class="w-full bg-white border border-gray-200 rounded-2xl px-6 py-4 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition shadow-sm resize-none" placeholder="Jalan, No. Rumah, RT/RW, Kecamatan" required>{{ $address->address_line ?? '' }}</textarea>
                                    </div>
                                </div>

                                <div class="pt-6">
                                    <button type="submit" class="bg-blue-500 text-white font-bold py-4 px-10 rounded-full hover:bg-blue-600 transition transform hover:scale-[1.02] shadow-lg shadow-blue-500/30">
                                        Simpan Perubahan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div x-show="activeTab === 'pesanan'" x-transition.opacity.duration.300ms style="display: none;" x-data="{ orderStatusTab: 'menunggu' }">
                    <div class="bg-white p-10 rounded-2xl shadow-sm border border-gray-100 min-h-[600px]">
                        <h2 class="text-xl font-bold mb-8 text-gray-800">Daftar Pesanan</h2>
                        
                        <div class="flex border-b border-gray-200 mb-8 gap-8">
                            <button @click="orderStatusTab = 'menunggu'" :class="orderStatusTab === 'menunggu' ? 'border-b-2 border-blue-500 text-blue-500 font-bold' : 'text-gray-400 font-medium hover:text-gray-600'" class="pb-4 text-sm transition">
                                Menunggu Pembayaran
                            </button>
                            <button @click="orderStatusTab = 'status'" :class="orderStatusTab === 'status' ? 'border-b-2 border-blue-500 text-blue-500 font-bold' : 'text-gray-400 font-medium hover:text-gray-600'" class="pb-4 text-sm transition">
                                Status Pesanan
                            </button>
                        </div>

                        <div x-show="orderStatusTab === 'menunggu'">
                            @forelse($unpaidOrders as $order)
                            <div class="border border-gray-200 bg-white rounded-xl mb-6 hover:shadow-md transition duration-300 overflow-hidden">
                                <div class="flex justify-between items-center border-b border-gray-100 px-6 py-4 bg-[#fcfcfc]">
                                    <div class="flex items-center gap-3">
                                        <i class="fa-solid fa-bag-shopping text-blue-500"></i>
                                        <span class="font-bold text-sm text-gray-800">Belanja</span>
                                        <span class="text-xs text-gray-400">{{ $order->created_at->format('d M Y') }}</span>
                                        <span class="bg-red-50 text-red-500 border border-red-200 text-[10px] font-bold px-2 py-1 rounded-sm uppercase tracking-wider">Belum Dibayar</span>
                                    </div>
                                    <div class="text-xs text-gray-500 font-medium hidden md:block">
                                        {{ $order->invoice_number }}
                                    </div>
                                </div>

                                <div class="px-6 py-5 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                                    <div class="flex-1 w-full" x-data="{ lihatSemua: false }">
                                        @php
                                            $firstItem = $order->orderItems->first();
                                        @endphp
                                        
                                        @if($firstItem)
                                        <div class="flex gap-4 items-start">
                                            <div class="w-16 h-16 bg-white border border-gray-200 rounded-lg overflow-hidden flex-shrink-0 p-1">
                                                @if($firstItem->productVariant && $firstItem->productVariant->product->mainImage)
                                                    <img src="{{ asset('images/' . $firstItem->productVariant->product->mainImage->image_path) }}" class="w-full h-full object-contain">
                                                @else
                                                    <img src="{{ asset('images/default.jpg') }}" class="w-full h-full object-contain">
                                                @endif
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-sm text-gray-800 line-clamp-1 mb-1">{{ $firstItem->product_name }}</h4>
                                                <p class="text-xs text-gray-500">{{ $firstItem->quantity }} barang x Rp {{ number_format($firstItem->price, 0, ',', '.') }}</p>
                                            </div>
                                        </div>
                                        @endif

                                        @if($order->orderItems->count() > 1)
                                            <div x-show="lihatSemua" x-transition class="mt-4 pt-4 border-t border-gray-100 space-y-4" style="display: none;">
                                                @foreach($order->orderItems->skip(1) as $item)
                                                <div class="flex gap-4 items-start">
                                                    <div class="w-16 h-16 bg-white border border-gray-200 rounded-lg overflow-hidden flex-shrink-0 p-1">
                                                        @if($item->productVariant && $item->productVariant->product->mainImage)
                                                            <img src="{{ asset('images/' . $item->productVariant->product->mainImage->image_path) }}" class="w-full h-full object-contain">
                                                        @else
                                                            <img src="{{ asset('images/default.jpg') }}" class="w-full h-full object-contain">
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <h4 class="font-bold text-sm text-gray-800 line-clamp-1 mb-1">{{ $item->product_name }}</h4>
                                                        <p class="text-xs text-gray-500">{{ $item->quantity }} barang x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>

                                            <button @click="lihatSemua = !lihatSemua" class="text-xs text-blue-500 font-bold mt-3 hover:underline flex items-center gap-1 focus:outline-none">
                                                <span x-show="!lihatSemua">+ {{ $order->orderItems->count() - 1 }} produk lainnya <i class="fa-solid fa-chevron-down ml-1 text-[10px]"></i></span>
                                                <span x-show="lihatSemua" style="display: none;">Sembunyikan produk <i class="fa-solid fa-chevron-up ml-1 text-[10px]"></i></span>
                                            </button>
                                        @endif
                                    </div>

                                    <div class="flex flex-col md:items-end w-full md:w-auto border-t md:border-t-0 md:border-l border-gray-100 pt-4 md:pt-0 md:pl-6">
                                        <p class="text-xs text-gray-500 mb-1">Total Belanja</p>
                                        <p class="font-bold text-lg text-gray-900 mb-4">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</p>
                                        
                                        <div class="flex gap-3 w-full justify-end">
    
                                        <button type="button" 
                                                @click="$dispatch('trigger-cancel', { url: '{{ route('order.cancel', $order->id) }}' })" 
                                                class="bg-white border border-red-200 text-red-500 text-xs font-bold px-5 py-2.5 rounded-lg hover:bg-red-50 transition">
                                            Batalkan
                                        </button>

                                        <a href="#" class="bg-blue-500 text-white text-xs font-bold px-6 py-2.5 rounded-lg hover:bg-blue-600 transition shadow-md shadow-blue-500/20 text-center flex items-center">
                                            Bayar Sekarang
                                        </a>
                                        
                                    </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-20 text-gray-400">
                                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                <p class="font-medium text-lg">Belum ada tagihan pembayaran.</p>
                            </div>
                            @endforelse
                        </div>

                        <div x-show="orderStatusTab === 'status'" style="display: none;">
                            @forelse($activeOrders as $order)
                            <div class="border border-gray-100 rounded-2xl p-6 mb-6 relative overflow-hidden hover:shadow-sm transition">
                                <div class="flex justify-between border-b border-gray-100 pb-4 mb-4">
                                    <div>
                                        <h3 class="font-bold text-gray-900">{{ $order->invoice_number }}</h3>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs text-gray-500 mb-2">Tanggal: {{ $order->created_at->format('d M Y') }}</p>
                                        <span class="inline-block px-4 py-1 bg-green-50 text-green-600 text-[10px] uppercase tracking-wider font-bold rounded-full border border-green-200">
                                            @if($order->status == 'paid') Menunggu Kurir 
                                            @elseif($order->status == 'processing') Sedang Dikemas
                                            @elseif($order->status == 'shipped') Dikirim
                                            @endif
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="flex justify-between items-end">
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Total Belanja</p>
                                        <p class="font-bold text-xl text-gray-900">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</p>
                                    </div>
                                    <a href="#" class="bg-[#f8f8f8] border border-gray-200 text-gray-700 hover:text-blue-500 hover:border-blue-200 font-bold px-5 py-2.5 rounded-full text-sm flex items-center gap-2 transition">
                                        Cetak Struk
                                    </a>
                                </div>

                                <div class="mt-8 pt-6 border-t border-gray-100 relative">
                                    <div class="overflow-hidden h-2.5 mb-4 text-xs flex rounded-full bg-[#f8f8f8] border border-gray-100">
                                        <div style="width: {{ $order->status == 'shipped' ? '100%' : '50%' }}" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-green-400"></div>
                                    </div>
                                    <div class="flex justify-between text-[11px] uppercase tracking-wider font-bold text-gray-400">
                                        <span class="{{ $order->status != 'pending' ? 'text-green-500' : '' }}">Sedang Dibuat</span>
                                        <span class="{{ $order->status == 'shipped' ? 'text-green-500' : '' }}">Dikirim</span>
                                        <span>Sampai Tujuan</span>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-20 text-gray-400">
                                <p class="font-medium text-lg">Belum ada pesanan yang aktif.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div x-show="activeTab === 'favorit'" style="display: none;" x-transition.opacity.duration.300ms>
                    <div class="bg-white p-10 rounded-2xl shadow-sm border border-gray-100 text-center py-20">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                        <h2 class="text-xl font-bold mb-2 text-gray-800">Belum Ada Favorit</h2>
                        <p class="text-gray-500">Anda belum menambahkan produk ke daftar favorit.</p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
    .page-enter {
        opacity: 0;
        transform: translateY(15px);
        animation: fadeInUp 0.6s ease-out forwards;
    }
    @keyframes fadeInUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<script>
    // Fitur Preview Gambar saat upload (TETAP SAMA)
    function previewFile(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                // Langsung tembak ID gambar untuk diganti source-nya
                document.getElementById('preview-image').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    // Fitur AJAX Dropdown Provinsi -> Kabupaten (TETAP SAMA)
    document.addEventListener('DOMContentLoaded', function() {
        const provinceSelect = document.getElementById('province_id');
        const citySelect = document.getElementById('city_id');

        provinceSelect.addEventListener('change', function() {
            const provinceId = this.value;
            
            // Kosongkan dan set loading pada dropdown kota
            citySelect.innerHTML = '<option value="">Memuat data...</option>';
            
            if(provinceId) {
                // Fetch data dari rute API yang kita buat di Controller
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
                    })
                    .catch(error => {
                        console.error('Error fetching cities:', error);
                        citySelect.innerHTML = '<option value="">Gagal memuat data</option>';
                    });
            } else {
                citySelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
            }
        });
    });
</script>
@endsection