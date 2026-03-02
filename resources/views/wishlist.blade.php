@extends('layouts.app')

@section('content')
<div class="bg-white min-h-screen pb-20" x-data="{ selectedIds: [], selectAll: false, toggleAll() { if(this.selectAll) { this.selectedIds = {{ $wishlistItems->pluck('id') }} } else { this.selectedIds = [] } } }">
    
    <div class="pt-10 pb-6 text-center">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900">Favorite</h1>
    </div>

    <div class="max-w-6xl mx-auto px-4 md:px-6">
        
        <div class="mb-8 border-b border-gray-200 pb-4 w-full max-w-md mx-auto">
            <label class="text-xs text-gray-500 block mb-1">Kategori Produk</label>
            <form action="{{ route('wishlist.index') }}" method="GET">
                <select name="kategori" onchange="this.form.submit()" class="w-full font-bold text-gray-800 bg-transparent border-none focus:ring-0 cursor-pointer outline-none">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->slug }}" {{ request('kategori') == $cat->slug ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="flex flex-wrap justify-between items-center mb-6 gap-4">
            <label class="flex items-center gap-2 cursor-pointer font-bold text-sm text-gray-700">
                <input type="checkbox" x-model="selectAll" @change="toggleAll" class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                Pilih Semua
            </label>

            <div class="flex flex-wrap gap-2">
                <form action="{{ route('wishlist.destroyBulk') }}" method="POST" id="bulkDeleteForm">
                    @csrf
                    <template x-for="id in selectedIds">
                        <input type="hidden" name="ids[]" :value="id">
                    </template>
                    <button type="submit" :disabled="selectedIds.length === 0" class="flex items-center gap-2 border border-red-300 rounded-full px-4 md:px-6 py-2 text-xs md:text-sm text-red-600 hover:bg-red-50 transition disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fa-regular fa-trash-can"></i> Hapus Terpilih
                    </button>
                </form>

                <button class="flex items-center gap-2 border border-blue-300 rounded-full px-4 md:px-6 py-2 text-xs md:text-sm text-blue-600 hover:bg-blue-50 transition">
                    <i class="fa-solid fa-cart-shopping"></i> Masukkan Terpilih ke Keranjang
                </button>
            </div>
        </div>

        <div class="flex justify-between items-center border-b-2 border-black pb-4 mb-6">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-list text-lg md:text-xl"></i>
                <h2 class="text-lg md:text-xl font-bold">Daftar Belanja</h2>
            </div>
            <div class="flex gap-4 md:gap-12 text-gray-600 text-sm md:text-base font-medium">
                <span>{{ $wishlistItems->total() }} Produk</span>
            </div>
        </div>

        <div class="space-y-6 md:space-y-8">
            @forelse($wishlistItems as $index => $item)
            <div class="flex flex-col md:flex-row items-start gap-4 md:gap-8 border-b border-gray-100 pb-6 md:pb-8 relative">
                
                <div class="absolute top-0 right-0 md:static md:mt-16 z-10">
                    <input type="checkbox" value="{{ $item->id }}" x-model="selectedIds" class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                </div>

                <a href="{{ route('produk.show', $item->product->id) }}" class="w-full md:w-48 h-48 flex-shrink-0 bg-gray-50 rounded-xl p-4">
                    <img src="{{ asset('images/' . ($item->product->mainImage->image_path ?? 'default.jpg')) }}" class="w-full h-full object-contain">
                </a>

                <div class="flex-grow pt-2 w-full">
                    <div class="flex flex-col md:flex-row justify-between items-start gap-4">
                        <div class="space-y-2 md:space-y-4">
                            <p class="text-[10px] md:text-xs text-gray-400 font-bold uppercase tracking-widest">{{ $item->product->category->name ?? 'Uncategorized' }}</p>
                            <a href="{{ route('produk.show', $item->product->id) }}" class="text-base md:text-lg font-bold hover:text-blue-600 transition line-clamp-2">
                                {{ $item->product->name }}
                            </a>
                            
                            <div class="bg-yellow-300 inline-block px-3 py-1 shadow-sm">
                                <span class="text-black font-bold">Rp{{ number_format($item->product->starting_price, 0, ',', '.') }}</span>
                            </div>

                            <div class="text-[10px] md:text-xs text-gray-500 mt-2">
                                <p>Stok Tersedia: {{ $item->product->variants->sum('stock') }}</p>
                            </div>
                        </div>

                        <div class="flex flex-row md:flex-col items-center md:items-end gap-4 mt-4 md:mt-0 w-full md:w-auto justify-end">
                            
                            <div class="flex items-center gap-3">
                                <form action="{{ route('wishlist.destroy', $item->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Hapus dari Favorit" class="text-gray-400 hover:text-red-500 text-lg transition transform hover:scale-110">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </form>
                                
                                <form action="{{ route('cart.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_variant_id" value="{{ $item->product->variants->first()->id ?? '' }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" title="Tambah ke Keranjang" class="bg-yellow-300 w-10 h-10 rounded-full flex items-center justify-center hover:bg-yellow-400 transition transform hover:scale-110 shadow-md border border-black">
                                        <i class="fa-solid fa-cart-plus text-black text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-20">
                <i class="fa-regular fa-heart text-6xl text-gray-200 mb-4"></i>
                <h3 class="text-xl font-bold text-gray-600">Wishlist Anda Masih Kosong</h3>
                <p class="text-gray-400 text-sm mt-2">Yuk cari produk alat kesehatan yang Anda butuhkan!</p>
                <a href="{{ route('produk.index') }}" class="inline-block mt-6 px-6 py-2 bg-blue-600 text-white font-bold rounded-full hover:bg-blue-700 transition">Belanja Sekarang</a>
            </div>
            @endforelse
        </div>

        <div class="mt-10 md:mt-16">
            {{ $wishlistItems->withQueryString()->links('pagination::tailwind') }}
        </div>

    </div>
</div>
@endsection