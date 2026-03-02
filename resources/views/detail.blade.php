@extends('layouts.app')

@section('content')

@php
    // Format Data Varian
    $variantData = $product->variants->map(function($v) {
        return [
            'id' => $v->id,
            'name' => $v->variant_name,
            'sku' => $v->sku,
            'price' => 'Rp' . number_format($v->price, 0, ',', '.'),
            'stock' => $v->stock,
            'weight' => $v->weight_gram . ' Gram'
        ];
    });

    // Format Data Gambar
    $imageData = $product->images->map(function($img) {
        return asset('images/' . $img->image_path);
    });
    // Jika tidak ada gambar, pasang default
    if(count($imageData) == 0) $imageData[] = asset('images/default.jpg');
    $isWishlisted = auth()->check() ? auth()->user()->wishlists()->where('product_id', $product->id)->exists() : false;
@endphp

<div class="bg-white px-4 md:px-10 py-6 md:py-10 min-h-screen"
     x-data="{ 
         variants: {{ json_encode($variantData) }},
         selectedVariant: {{ json_encode($variantData[0] ?? null) }},
         
         images: {{ json_encode($imageData) }},
         currentIndex: 0,
         
         get mainImage() { return this.images[this.currentIndex]; },
         nextImg() { this.currentIndex = (this.currentIndex === this.images.length - 1) ? 0 : this.currentIndex + 1; },
         prevImg() { this.currentIndex = (this.currentIndex === 0) ? this.images.length - 1 : this.currentIndex - 1; },
         
         qty: 1,
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
                 body: JSON.stringify({ product_id: {{ $product->id }} })
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
                     // Pancarkan sinyal ke Header untuk update angka pin merah
                     window.dispatchEvent(new CustomEvent('wishlist-updated', { detail: data.wishlist_count }));
                 }
             })
             .catch(error => console.log('Error:', error));
         },
         addToCart() {
             fetch('{{ route('cart.store') }}', {
                 method: 'POST',
                 headers: {
                     'Content-Type': 'application/json',
                     'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                     'Accept': 'application/json'
                 },
                 body: JSON.stringify({ 
                     product_variant_id: this.selectedVariant.id, 
                     quantity: this.qty 
                 })
             })
             .then(response => {
                 if (response.status === 401) {
                     window.location.href = '/login-register'; 
                     return Promise.reject('Belum Login');
                 }
                 return response.json();
             })
             .then(data => {
                 if (data.success) {
                     // Kirim data baru ke Pop-up Header
                     window.dispatchEvent(new CustomEvent('cart-updated', { 
                         detail: { count: data.cart_count, items: data.cart_items } 
                     }));
                 }
             })
             .catch(error => console.log('Error:', error));
         }
     }">
    
    <nav class="text-xs md:text-sm text-gray-400 mb-6 md:mb-8">
        <a href="/" class="hover:text-blue-600">Beranda</a> / 
        <a href="{{ route('produk.index') }}" class="hover:text-blue-600">Produk</a> / 
        <span class="text-gray-800 font-medium truncate">{{ $product->name }}</span>
    </nav>

    <div class="flex flex-col lg:flex-row gap-8 md:gap-10 mb-10 md:mb-20">
        
        <aside class="w-full lg:w-1/4 space-y-6 md:space-y-8 order-2 lg:order-1 mt-10 lg:mt-0">
            
            <div class="bg-[#f8f8f8] p-5 md:p-6 rounded-xl border border-gray-200/50"
                 x-data="{ 
                     query: '', results: [], isLoading: false, hasSearched: false,
                     fetchResults() {
                         if(this.query.length < 2) { this.results = []; this.hasSearched = false; return; }
                         this.isLoading = true; this.hasSearched = true;
                         fetch(`/api/search-products?q=${this.query}`)
                             .then(res => res.json())
                             .then(data => { this.results = data; this.isLoading = false; });
                     }
                 }">
                <h3 class="text-base md:text-lg font-bold mb-4 border-b-2 border-blue-500 w-max">Cari Langsung</h3>
                <div class="relative z-50">
                    <input type="text" x-model="query" @input.debounce.500ms="fetchResults" placeholder="Ketik nama produk..." 
                           class="w-full bg-white border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                    <i class="fa-solid fa-magnifying-glass absolute right-3 top-2.5 md:top-3 text-gray-400"></i>
                    
                    <div x-show="query.length >= 2" x-cloak class="absolute top-full left-0 w-full mt-2 bg-white border border-gray-100 shadow-xl rounded-lg overflow-hidden">
                        <template x-if="isLoading">
                            <div class="p-4 text-center text-xs text-gray-500">Mencari...</div>
                        </template>
                        <template x-if="!isLoading && results.length > 0">
                            <div>
                                <template x-for="item in results">
                                    <a :href="item.url" class="flex items-center gap-3 p-3 hover:bg-gray-50 border-b border-gray-50 transition">
                                        <img :src="item.image" class="w-10 h-10 object-cover rounded">
                                        <div>
                                            <h4 class="text-xs font-bold text-gray-800 line-clamp-1" x-text="item.name"></h4>
                                            <p class="text-[10px] text-blue-500 font-bold" x-text="item.price"></p>
                                        </div>
                                    </a>
                                </template>
                            </div>
                        </template>
                        <template x-if="!isLoading && results.length === 0 && hasSearched">
                            <div class="p-4 text-center text-xs text-red-500 font-bold">Produk tidak ditemukan!</div>
                        </template>
                    </div>
                </div>
            </div>

            <div class="bg-[#f8f8f8] p-5 md:p-6 rounded-xl border border-gray-200/50">
                <h3 class="text-base md:text-lg font-bold mb-4 border-b-2 border-blue-500 w-max">Produk Unggulan</h3>
                <div class="space-y-4">
                    @foreach($featured_products as $fp)
                    <a href="{{ route('produk.show', $fp->id) }}" class="flex gap-3 items-center group cursor-pointer">
                        <div class="w-14 h-14 md:w-16 md:h-16 bg-white rounded-lg flex-shrink-0 border border-gray-100 p-1">
                            <img src="{{ asset('images/' . ($fp->mainImage->image_path ?? 'default.jpg')) }}" class="w-full h-full object-contain">
                        </div>
                        <div>
                            <h4 class="text-[11px] md:text-xs font-bold group-hover:text-blue-600 transition line-clamp-2">{{ $fp->name }}</h4>
                            <p class="text-blue-500 font-bold text-[11px] md:text-xs mt-1">Rp{{ number_format($fp->starting_price, 0, ',', '.') }}</p>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
        </aside>

        <div class="w-full lg:w-3/4 order-1 lg:order-2">
            
            <div class="flex flex-col md:flex-row gap-6 md:gap-10">
                
                <div class="w-full md:w-1/2">
                    <div class="aspect-square bg-[#f8f8f8] md:bg-white rounded-2xl p-4 md:p-8 overflow-hidden relative border border-gray-100 md:border-none group">
                        <img :src="mainImage" class="w-full h-full object-contain mx-auto transition-all duration-300">
                        
                        <button @click="prevImg()" class="absolute left-2 top-1/2 transform -translate-y-1/2 bg-white w-8 h-8 rounded-full shadow-md text-gray-500 hover:text-blue-500 md:opacity-0 group-hover:opacity-100 transition"><i class="fa-solid fa-chevron-left"></i></button>
                        <button @click="nextImg()" class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-white w-8 h-8 rounded-full shadow-md text-gray-500 hover:text-blue-500 md:opacity-0 group-hover:opacity-100 transition"><i class="fa-solid fa-chevron-right"></i></button>
                    </div>
                    
                    <div class="grid grid-cols-4 gap-2 md:gap-4 mt-4">
                        <template x-for="(img, index) in images" :key="index">
                            <div @click="currentIndex = index" 
                                 class="aspect-square bg-white rounded-xl border-2 cursor-pointer transition-all duration-300 p-1 md:p-2 overflow-hidden"
                                 :class="currentIndex === index ? 'border-blue-500' : 'border-gray-100 md:border-transparent hover:border-gray-200'">
                                <img :src="img" class="w-full h-full object-contain">
                            </div>
                        </template>
                    </div>
                </div>

                <div class="w-full md:w-1/2 mt-4 md:mt-0">
                    <p class="text-[10px] md:text-xs font-bold text-gray-400 uppercase tracking-widest">{{ $product->category->name ?? 'Uncategorized' }}</p>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mt-1 md:mt-2 leading-tight">{{ $product->name }}</h1>
                    
                    <div class="flex items-center gap-3 md:gap-4 mt-3 md:mt-4">
                        <span class="text-xl md:text-3xl font-bold text-blue-600" x-text="selectedVariant.price"></span>
                    </div>

                    <p class="text-gray-500 text-xs md:text-sm leading-relaxed mt-4 md:mt-6">
                        {{ $product->description }}
                    </p>

                    <div class="mt-6">
                        <p class="text-xs font-bold text-gray-800 mb-2">Pilih Ukuran/Varian:</p>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="v in variants" :key="v.id">
                                <button @click="selectedVariant = v; qty = 1;"
                                        class="px-4 py-2 border rounded-md text-xs font-bold transition-all"
                                        :class="selectedVariant.id === v.id ? 'border-blue-500 bg-blue-50 text-blue-600' : 'border-gray-200 text-gray-500 hover:border-blue-300'">
                                    <span x-text="v.name"></span>
                                </button>
                            </template>
                        </div>
                        <p class="text-[10px] mt-2 font-bold" :class="selectedVariant.stock > 0 ? 'text-green-500' : 'text-red-500'">
                            Stok Tersedia: <span x-text="selectedVariant.stock"></span>
                        </p>
                    </div>

                    <div class="mt-6 md:mt-8 flex flex-wrap sm:flex-nowrap items-center gap-3 md:gap-4">
                        
                        <div class="flex border border-gray-200 rounded-full px-3 md:px-4 py-2.5 md:py-3 gap-3 md:gap-4 items-center bg-white">
                            <button @click="if(qty > 1) qty--" class="text-gray-400 hover:text-blue-500 transition px-1"><i class="fa-solid fa-minus text-xs"></i></button>
                            <span class="font-bold w-4 text-center text-gray-700 text-sm md:text-base" x-text="qty"></span>
                            <button @click="if(qty < selectedVariant.stock) qty++" class="text-gray-400 hover:text-blue-500 transition px-1"><i class="fa-solid fa-plus text-xs"></i></button>
                        </div>

                        <button @click.prevent="addToCart()" type="button" class="flex-1 bg-blue-500 text-white px-4 md:px-8 py-3 rounded-full font-bold text-sm hover:bg-blue-600 transition-all transform hover:scale-105 shadow-lg shadow-blue-500/30 text-center disabled:opacity-50 disabled:cursor-not-allowed" :disabled="selectedVariant.stock < 1">
                            <i class="fa-solid fa-cart-shopping mr-2"></i> Tambah Keranjang
                        </button>

                        <div class="flex gap-3 mt-2 sm:mt-0">
                            <button @click.prevent="toggleWishlist()" type="button" :title="isWishlisted ? 'Sudah di Favorit' : 'Tambah ke Wishlist'" 
                                    class="w-10 h-10 md:w-12 md:h-12 rounded-full border flex items-center justify-center transition-all group hover:shadow-md"
                                    :class="isWishlisted ? 'bg-red-50 border-red-200 text-red-500' : 'bg-white border-gray-200 md:border-gray-100 text-gray-600 hover:text-red-500 hover:border-red-100'">
                                <i class="transition-transform group-hover:scale-110" :class="isWishlisted ? 'fa-solid fa-heart' : 'fa-regular fa-heart'"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-12 md:mt-20 border-t border-gray-100 pt-8 md:pt-10">
                <h2 class="text-lg md:text-xl font-bold border-b-2 border-blue-500 w-max pb-2 mb-4 md:mb-6">Deskripsi</h2>
                <div class="text-gray-500 text-xs md:text-sm space-y-3 md:space-y-4 leading-relaxed max-w-3xl">
                    <p>{{ $product->description }}</p>
                </div>

                <div class="mt-10 md:mt-12 pt-8 md:pt-10 border-t border-gray-100">
                    <h2 class="text-lg md:text-xl font-bold border-b-2 border-blue-500 w-max pb-2 mb-4 md:mb-6">Informasi Tambahan</h2>
                    <table class="w-full max-w-xl text-xs md:text-sm">
                        <tr class="border-b border-gray-50">
                            <td class="py-2 md:py-3 font-bold text-gray-800 w-1/3 italic">Berat (Gram)</td>
                            <td class="py-2 md:py-3 text-gray-500" x-text="selectedVariant.weight"></td>
                        </tr>
                        <tr class="border-b border-gray-50">
                            <td class="py-2 md:py-3 font-bold text-gray-800 w-1/3 italic">Kondisi</td>
                            <td class="py-2 md:py-3 text-gray-500">Baru & Steril</td>
                        </tr>
                    </table>
                </div>
            </div>
            
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

<div class="border-t border-gray-100 pt-10 md:pt-16 pb-12 md:pb-20 bg-[#f8f8f8] md:bg-white px-4 md:px-10 w-full"
     x-data="{
         scrollNext() { $refs.slider.scrollBy({left: 300, behavior: 'smooth'}); },
         scrollPrev() { $refs.slider.scrollBy({left: -300, behavior: 'smooth'}); }
     }">
    <div class="max-w-7xl mx-auto relative">
        <div class="flex justify-between items-center mb-6 md:mb-12">
            <h2 class="text-xl md:text-2xl font-bold">Produk Serupa</h2>
            <div class="flex gap-2">
                <button @click="scrollPrev" class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center hover:bg-blue-500 hover:text-white transition shadow-sm"><i class="fa-solid fa-chevron-left text-xs"></i></button>
                <button @click="scrollNext" class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center hover:bg-blue-500 hover:text-white transition shadow-sm"><i class="fa-solid fa-chevron-right text-xs"></i></button>
            </div>
        </div>
        
        <div x-ref="slider" class="flex overflow-x-auto snap-x snap-mandatory gap-4 md:gap-8 pb-4 hide-scrollbar">
            @forelse($related_products as $rp)
            <a href="{{ route('produk.show', $rp->id) }}" class="snap-start flex-none w-[45%] md:w-[30%] lg:w-[18%] group cursor-pointer bg-white md:bg-transparent p-3 md:p-0 rounded-xl md:rounded-none shadow-sm md:shadow-none border border-gray-100 md:border-none">
                <div class="aspect-square bg-[#f8f8f8] md:bg-white rounded-xl md:rounded-2xl p-2 md:p-4 relative overflow-hidden mb-3 md:mb-4">
                    <img src="{{ asset('images/' . ($rp->mainImage->image_path ?? 'default.jpg')) }}" class="w-full h-full object-contain group-hover:scale-110 transition duration-500">
                </div>
                
                <p class="text-[8px] md:text-[10px] text-gray-400 font-bold uppercase tracking-wider">{{ $rp->category->name ?? 'Uncategorized' }}</p>
                <h4 class="font-bold text-gray-800 text-xs md:text-sm mt-1 group-hover:text-blue-600 transition truncate">{{ $rp->name }}</h4>
                <div class="flex gap-2 mt-1">
                    <span class="text-blue-500 font-bold text-xs md:text-sm">Rp{{ number_format($rp->starting_price, 0, ',', '.') }}</span>
                </div>
            </a>
            @empty
            <div class="text-gray-400 text-sm">Belum ada produk serupa di kategori ini.</div>
            @endforelse
        </div>
    </div>
</div>

<style>
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    [x-cloak] { display: none !important; }
</style>
@endsection