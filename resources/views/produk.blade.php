@extends('layouts.app')

@section('content')
<div class="bg-white px-4 md:px-10 py-6 md:py-10 flex flex-col lg:flex-row gap-6 md:gap-8 min-h-screen">
    
    <aside class="w-full lg:w-1/4 xl:w-[280px] flex-shrink-0 relative">
        
        <div class="sticky top-28 flex flex-col gap-6">
            
            <form action="{{ route('produk.index') }}" method="GET" class="flex flex-col gap-6 w-full">
                
                <div class="bg-[#f8f8f8] p-5 md:p-6 rounded-2xl border border-gray-200/60 shadow-sm w-full">
                    <h3 class="text-base md:text-lg font-bold mb-4 border-b-2 border-blue-500 w-max pb-1">Kategori</h3>
                    <ul class="space-y-3 text-xs md:text-sm text-gray-600 font-medium max-h-[200px] overflow-y-auto custom-scrollbar pr-3">
                        @foreach($categories as $cat)
                        <li class="hover:text-blue-600 cursor-pointer flex justify-between {{ request('kategori') == $cat->slug ? 'text-blue-600 font-bold' : '' }}">
                            <a href="{{ request()->fullUrlWithQuery(['kategori' => $cat->slug]) }}" class="w-full flex justify-between items-center">
                                <span class="truncate pr-2">{{ $cat->name }}</span>
                                <span class="bg-white border border-gray-200 text-[10px] px-2 py-0.5 rounded-full shadow-sm">{{ $cat->products_count }}</span>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                    @if(request('kategori'))
                        <a href="{{ route('produk.index', request()->except('kategori')) }}" class="text-[10px] text-red-500 mt-4 flex items-center gap-1 font-bold hover:underline">
                            <i class="fa-solid fa-xmark"></i> Reset Kategori
                        </a>
                    @endif
                </div>

                <div class="bg-[#f8f8f8] p-5 md:p-6 rounded-2xl border border-gray-200/60 shadow-sm w-full">
                    <h3 class="text-base md:text-lg font-bold mb-4 border-b-2 border-blue-500 w-max pb-1">Varian Ukuran</h3>
                    <ul class="space-y-3 text-xs md:text-sm text-gray-600 font-medium max-h-[150px] overflow-y-auto custom-scrollbar pr-3">
                        @foreach($sizes as $size)
                        <li class="hover:text-blue-600 cursor-pointer {{ request('ukuran') == $size->variant_name ? 'text-blue-600 font-bold' : '' }}">
                            <a href="{{ request()->fullUrlWithQuery(['ukuran' => $size->variant_name]) }}" class="block w-full">
                                <div class="flex items-center gap-2">
                                    <div class="w-3 h-3 rounded border {{ request('ukuran') == $size->variant_name ? 'bg-blue-500 border-blue-500' : 'border-gray-300 bg-white' }}"></div>
                                    <span class="truncate">{{ $size->variant_name }}</span>
                                </div>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <div class="bg-[#f8f8f8] p-5 md:p-6 rounded-2xl border border-gray-200/60 shadow-sm w-full" 
                     x-data="{ minPrice: '{{ request('min_price', '') }}', maxPrice: '{{ request('max_price', '') }}' }">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-base md:text-lg font-bold border-b-2 border-blue-500 pb-1">Batas Harga</h3>
                        @if(request('min_price') || request('max_price'))
                            <a href="{{ request()->fullUrlWithQuery(['min_price' => null, 'max_price' => null]) }}" class="text-[10px] text-red-500 font-bold hover:underline">Hapus</a>
                        @endif
                    </div>
                    
                    <div class="flex items-center gap-2 mb-4">
                        <div class="relative flex items-center w-full">
                            <span class="absolute left-3 text-gray-500 text-xs font-bold">Rp</span>
                            <input type="number" name="min_price" x-model="minPrice" min="0" 
                                   class="w-full pl-8 pr-2 py-2.5 text-xs font-bold text-gray-700 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 appearance-none shadow-inner" 
                                   placeholder="MIN">
                        </div>
                        <span class="text-gray-400 font-bold">-</span>
                        <div class="relative flex items-center w-full">
                            <span class="absolute left-3 text-gray-500 text-xs font-bold">Rp</span>
                            <input type="number" name="max_price" x-model="maxPrice" min="0" 
                                   class="w-full pl-8 pr-2 py-2.5 text-xs font-bold text-gray-700 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 appearance-none shadow-inner" 
                                   placeholder="MAKS">
                        </div>
                    </div>
                    
                    <div class="mb-6 px-1">
                        <input type="range" x-model="maxPrice" min="0" max="5000000" step="50000" 
                               class="w-full h-1.5 bg-gray-300 rounded-lg appearance-none cursor-pointer accent-blue-600">
                        <div class="flex justify-between text-[9px] text-gray-400 mt-2 font-bold uppercase">
                            <span>0</span>
                            <span>5 Juta+</span>
                        </div>
                    </div>
                    
                    @if(request('kategori')) <input type="hidden" name="kategori" value="{{ request('kategori') }}"> @endif
                    @if(request('ukuran')) <input type="hidden" name="ukuran" value="{{ request('ukuran') }}"> @endif
                    
                    <button type="submit" class="w-full bg-blue-600 border border-transparent py-3 rounded-xl text-xs md:text-sm font-bold text-white hover:bg-blue-700 hover:shadow-lg shadow-blue-500/30 transition-all flex justify-center items-center gap-2">
                        <i class="fa-solid fa-filter text-[10px]"></i> Terapkan Filter
                    </button>
                </div>
                
            </form>
        </div>
    </aside>

    <div class="w-full lg:w-3/4">
        
        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-3 md:gap-6">
            @forelse($allProducts as $index => $p)
            @php
                $isWishlisted = auth()->check() ? auth()->user()->wishlists()->where('product_id', $p->id)->exists() : false;
                $hoverImage = $p->images->where('is_main', 0)->first(); // Mengambil 1 gambar yang bukan main
            @endphp

            <div x-data="{ 
                    shown: false,
                    showSuccessModal: false,
                    isWishlisted: {{ $isWishlisted ? 'true' : 'false' }},
                    toggleWishlist() {
                        fetch('{{ route('wishlist.store') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ product_id: {{ $p->id }} })
                        })
                        .then(response => {
                            if (response.status === 401) {
                                window.location.href = '/login-register'; // Lempar ke login jika belum login
                                return Promise.reject('Belum Login');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                this.isWishlisted = data.is_wishlisted; // Ubah warna tombol
                                // Pancarkan sinyal gaib ke Header untuk ubah angka pin
                                window.dispatchEvent(new CustomEvent('wishlist-updated', { detail: data.wishlist_count }));
                            }
                        })
                        .catch(error => console.log('Error:', error));
                    },
                    addToCart(variantId) {
                        if(!variantId) return alert('Varian tidak tersedia');
                        fetch('{{ route('cart.store') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ product_variant_id: variantId, quantity: 1 })
                        })
                        .then(res => {
                            if(res.status === 401) window.location.href = '/login-register';
                            return res.json();
                        })
                        .then(data => {
                            if(data.success) {
                                window.dispatchEvent(new CustomEvent('cart-updated', { 
                                    detail: { count: data.cart_count, items: data.cart_items } 
                                }));
                            }
                        });
                    }
                }" 
                x-intersect.margin.20%="shown = true"
                :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'"
                class="group rounded-xl transition-all duration-700 ease-out relative border border-gray-100 hover:shadow-lg flex flex-col h-full bg-white overflow-hidden"
                style="transition-delay: {{ ($index % 4) * 100 }}ms">
                
                <div class="absolute top-2 left-2 z-10">
                    <span class="bg-yellow-500 text-white text-[8px] md:text-[10px] px-2 py-1 rounded font-bold uppercase tracking-wider">NEW</span>
                </div>
                
                <div class="h-48 md:h-60 w-full relative overflow-hidden bg-white">
                    <a href="{{ route('produk.show', $p->id) }}" class="block w-full h-full">
                        <img src="{{ asset('images/' . ($p->mainImage->image_path ?? 'default.jpg')) }}" 
                             class="w-full h-full object-cover transition-transform duration-700 {{ $hoverImage ? 'group-hover:opacity-0' : 'group-hover:scale-110' }}">
                        
                        @if($hoverImage)
                        <img src="{{ asset('images/' . $hoverImage->image_path) }}" 
                             class="w-full h-full object-cover absolute top-0 left-0 opacity-0 group-hover:opacity-100 group-hover:scale-110 transition-all duration-700">
                        @endif
                    </a>

                    <div class="absolute bottom-4 left-0 right-0 justify-center gap-2 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-y-4 group-hover:translate-y-0 hidden md:flex z-20">
                        
                        <button @click.prevent="addToCart({{ $p->variants->first()->id ?? 'null' }})" type="button" title="Tambah ke Keranjang" class="bg-white text-gray-800 w-10 h-10 rounded-full hover:bg-blue-600 hover:text-white shadow-md transition flex items-center justify-center relative z-30 transform hover:scale-110">
                            <i class="fa-solid fa-cart-shopping text-xs"></i>
                        </button>

                        <button @click.prevent="toggleWishlist()" type="button" title="Favorit" 
                                class="w-10 h-10 rounded-full shadow-md transition-all duration-300 flex items-center justify-center relative z-30 transform hover:scale-110"
                                :class="isWishlisted ? 'bg-red-500 text-white' : 'bg-white text-gray-800 hover:bg-red-50 hover:text-red-500'">
                            <i class="text-xs transition-colors duration-300" :class="isWishlisted ? 'fa-solid fa-heart' : 'fa-regular fa-heart'"></i>
                        </button>

                        <a href="{{ route('produk.show', $p->id) }}" title="Lihat Detail" class="bg-white text-gray-800 w-10 h-10 rounded-full hover:bg-blue-600 hover:text-white shadow-md transition flex items-center justify-center">
                            <i class="fa-solid fa-eye text-xs"></i>
                        </a>
                    </div>
                </div>

                <div class="p-4 flex flex-col flex-grow">
                    <p class="text-[8px] md:text-[10px] text-gray-400 font-bold uppercase tracking-wider">{{ $p->category->name ?? 'Uncategorized' }}</p>
                    
                    <a href="{{ route('produk.show', $p->id) }}">
                        <h4 class="font-bold text-gray-800 text-xs md:text-sm mt-1 hover:text-blue-600 transition h-8 md:h-10 overflow-hidden line-clamp-2">
                            {{ $p->name }}
                        </h4>
                    </a>
                    
                    <div class="mt-auto pt-2 flex items-baseline">
                        <span class="text-blue-600 font-bold text-sm md:text-base">Rp{{ number_format($p->starting_price, 0, ',', '.') }}</span>
                    </div>
                </div>
                <div x-show="showSuccessModal" 
         x-cloak 
         class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
         
            </div>
            </div>
            @empty
            <div class="col-span-full py-20 text-center text-gray-400">
                <p class="text-lg font-bold">Produk tidak ditemukan!</p>
                <p class="text-sm">Coba ubah filter atau kriteria pencarian Anda.</p>
            </div>
            @endforelse
            
        </div>
        
        <div class="mt-10 md:mt-16">
            {{ $allProducts->withQueryString()->links('pagination::tailwind') }}
        </div>
        
    </div>
</div>
@endsection