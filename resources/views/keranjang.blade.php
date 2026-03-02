@extends('layouts.app')

@section('content')
@php
    // Format data asli dari DB
    $formattedCarts = $cartItems->map(function($cart) {
        return [
            'id' => $cart->id,
            'name' => $cart->variant->product->name . ' (' . $cart->variant->variant_name . ')',
            'price' => $cart->variant->price,
            'qty' => $cart->quantity,
            'max_stock' => $cart->variant->stock,
            'img' => $cart->variant->product->mainImage ? asset('images/' . $cart->variant->product->mainImage->image_path) : asset('images/default.jpg')
        ];
    });
@endphp

<div class="bg-white min-h-screen pb-20"
     x-data="{ 
        items: {{ json_encode($formattedCarts) }},
        selectedIds: [],
        selectAll: false,
        
        toggleAll() {
            if(this.selectAll) { this.selectedIds = this.items.map(i => i.id); } 
            else { this.selectedIds = []; }
        },
        get total() {
            return this.items
                .filter(item => this.selectedIds.some(selectedId => selectedId == item.id))
                .reduce((acc, item) => acc + (item.price * item.qty), 0);
        },
        formatRupiah(number) {
            return 'Rp' + new Intl.NumberFormat('id-ID').format(number);
        }
     }">
    
    <div class="pt-10 pb-10 text-center px-4">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900">Keranjang Belanja</h1>
        <p class="text-gray-400 text-xs md:text-sm mt-2">Beranda / Keranjang</p>
    </div>

    <div class="max-w-6xl mx-auto px-4 md:px-6">
        
        <div class="flex flex-wrap justify-between items-center mb-6 gap-4">
            <label class="flex items-center gap-2 cursor-pointer font-bold text-sm text-gray-700">
                <input type="checkbox" x-model="selectAll" @change="toggleAll" class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                Pilih Semua
            </label>

            <form action="{{ route('cart.destroyBulk') }}" method="POST" id="bulkDeleteCartForm">
                @csrf
                <template x-for="id in selectedIds">
                    <input type="hidden" name="ids[]" :value="id">
                </template>
                <button type="submit" :disabled="selectedIds.length === 0" class="flex items-center gap-2 border border-red-300 rounded-full px-4 md:px-6 py-2 text-xs md:text-sm text-red-600 hover:bg-red-50 transition disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fa-regular fa-trash-can"></i> Hapus Terpilih
                </button>
            </form>
        </div>

        <form action="{{ route('cart.updateBulk') }}" method="POST">
            @csrf
            
            <div class="border border-gray-100 rounded-2xl overflow-x-auto mb-8 shadow-sm">
                <table class="w-full min-w-[600px]">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="py-4 px-4 text-center w-10">#</th>
                            <th class="py-4 px-6 text-left text-sm font-bold text-gray-600">Produk</th>
                            <th class="py-4 px-6 text-left text-sm font-bold text-gray-600">Harga</th>
                            <th class="py-4 px-6 text-left text-sm font-bold text-gray-600">Jumlah</th>
                            <th class="py-4 px-6 text-left text-sm font-bold text-gray-600">Subtotal</th>
                            <th class="py-4 px-6"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <template x-for="(item, index) in items" :key="item.id">
                            <tr class="transition-all duration-300 hover:bg-gray-50">
                                <td class="py-6 px-4 text-center">
                                    <input type="checkbox" :value="item.id" x-model="selectedIds" class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                                </td>

                                <td class="py-6 px-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-16 h-16 md:w-20 md:h-20 bg-white border border-gray-100 p-2 rounded-lg flex-shrink-0">
                                            <img :src="item.img" class="w-full h-full object-contain">
                                        </div>
                                        <span class="font-bold text-gray-800 text-xs md:text-sm line-clamp-2" x-text="item.name"></span>
                                    </div>
                                </td>

                                <td class="py-6 px-6">
                                    <div class="bg-yellow-300 inline-block px-3 py-1 shadow-sm rounded-sm">
                                        <span class="text-black font-bold text-xs md:text-sm" x-text="formatRupiah(item.price)"></span>
                                    </div>
                                </td>

                                <td class="py-6 px-6">
                                    <div class="flex items-center bg-white rounded-full px-3 py-1 w-max border border-gray-200">
                                        <button type="button" @click="if(item.qty > 1) item.qty--" class="text-gray-400 hover:text-blue-500 px-2">-</button>
                                        
                                        <input type="number" :name="`items[${item.id}]`" x-model.number="item.qty" class="w-8 text-center bg-transparent text-sm font-bold focus:outline-none appearance-none" min="1" :max="item.max_stock" readonly>
                                        
                                        <button type="button" @click="if(item.qty < item.max_stock) item.qty++" class="text-gray-400 hover:text-blue-500 px-2">+</button>
                                    </div>
                                </td>

                                <td class="py-6 px-6">
                                    <span class="text-blue-600 font-bold text-sm md:text-base" x-text="formatRupiah(item.price * item.qty)"></span>
                                </td>

                                <td class="py-6 px-6 text-right">
                                    <button type="button" @click="document.getElementById('deleteForm-' + item.id).submit()" class="text-gray-400 hover:text-red-500 text-xl transition transform hover:scale-110">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
                
                <div x-show="items.length === 0" class="p-10 text-center text-gray-500 font-bold">
                    Keranjang belanja Anda kosong.
                </div>
            </div>

            <div class="flex flex-col md:flex-row justify-between items-center mb-12 gap-6">
                <div class="flex flex-wrap gap-4 justify-center w-full md:w-auto">
                </div>
                
                <button type="submit" class="w-full md:w-auto bg-blue-500 text-white px-6 md:px-8 py-3 rounded-full font-bold text-xs md:text-sm hover:bg-blue-600 transition shadow-lg shadow-blue-500/30">
                    Update Keranjang
                </button>
            </div>
        </form>

        @foreach($cartItems as $item)
        <form id="deleteForm-{{ $item->id }}" action="{{ route('cart.destroy', $item->id) }}" method="POST" class="hidden">
            @csrf @method('DELETE')
        </form>
        @endforeach

        <div class="flex justify-center mb-12">
            {{ $cartItems->withQueryString()->links('pagination::tailwind') }}
        </div>

        <div class="flex justify-end">
            <div class="w-full md:w-1/2 lg:w-1/3 bg-[#f8f8f8] p-6 md:p-8 rounded-2xl border border-gray-200/50 shadow-sm">
                <h3 class="text-base md:text-lg font-bold border-b border-gray-200 pb-4 mb-4">Total Keranjang</h3>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-600 text-xs md:text-sm">Subtotal Sementara</span>
                    <span class="font-bold text-gray-800" x-text="formatRupiah(total)"></span>
                </div>
                <div class="flex justify-between items-center mb-6">
                    <span class="text-gray-600 text-xs md:text-sm">Pengiriman</span>
                    <span class="text-[10px] md:text-xs text-gray-400 italic">Dihitung saat checkout</span>
                </div>
                <div class="flex justify-between items-center border-t border-gray-200 pt-4 mb-6">
                    <span class="text-lg font-bold text-gray-900">Total</span>
                    <span class="text-xl md:text-2xl font-bold text-blue-600" x-text="formatRupiah(total)"></span>
                </div>
                
                <form action="/checkout" method="GET">
                    <template x-for="id in selectedIds">
                        <input type="hidden" name="selected_items[]" :value="id"> 
                    </template>
                    <button type="submit" :disabled="selectedIds.length === 0" class="block w-full bg-yellow-400 text-black font-bold py-3 md:py-4 rounded-full hover:bg-yellow-500 transition transform hover:scale-105 shadow-md text-center text-sm md:text-base disabled:opacity-50 disabled:cursor-not-allowed">
                        <span x-show="selectedIds.length > 0">Checkout Terpilih</span>
                        <span x-show="selectedIds.length === 0">Pilih Produk Dulu</span>
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection