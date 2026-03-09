@extends('admin.layouts.app')

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-black text-gray-800">Semua Produk</h1>
    </div>
    <div class="flex items-center gap-3 w-full md:w-auto">
        
        <div class="relative w-full md:w-72" x-data="{
            query: '',
            results: [],
            loading: false,
            search() {
                if(this.query.length < 2) { this.results = []; return; }
                this.loading = true;
                fetch(`/admin/produk/search?q=${this.query}`)
                    .then(res => res.json())
                    .then(data => { this.results = data; this.loading = false; });
            }
        }">
            <i class="fa-solid fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input type="text" x-model="query" @keyup.debounce.300ms="search" placeholder="Cari nama produk..." 
                   class="w-full bg-white border border-gray-200 rounded-xl pl-11 pr-4 py-2.5 text-sm focus:border-blue-500 focus:ring-1 outline-none shadow-sm">
            
            <div x-show="results.length > 0" @click.outside="results = []" class="absolute top-full left-0 w-full mt-2 bg-white rounded-xl shadow-2xl border border-gray-100 z-50 overflow-hidden">
                <template x-for="item in results" :key="item.id">
                    <a :href="`/admin/produk/${item.id}/edit`" class="flex items-center gap-3 p-3 border-b border-gray-50 hover:bg-slate-50 transition">
                        <img :src="item.main_image ? '/images/'+item.main_image.image_path : '/images/default.jpg'" class="w-10 h-10 rounded-md object-cover border border-gray-200">
                        <div>
                            <p class="text-sm font-bold text-gray-800 line-clamp-1" x-text="item.name"></p>
                        </div>
                    </a>
                </template>
            </div>
        </div>

        <a href="{{ route('admin.products.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-bold transition flex items-center gap-2 shadow-lg shadow-blue-500/30 whitespace-nowrap">
            <i class="fa-solid fa-plus"></i> Tambah Produk
        </a>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                    <th class="px-6 py-4 font-bold">Produk</th>
                    <th class="px-6 py-4 font-bold">Kategori</th>
                    <th class="px-6 py-4 font-bold">Varian & Stok</th>
                    <th class="px-6 py-4 font-bold text-center">Status Tampil</th>
                    <th class="px-6 py-4 font-bold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @forelse($products as $prod)
                <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <img src="{{ $prod->mainImage ? asset('images/' . $prod->mainImage->image_path) : asset('images/default.jpg') }}" class="w-12 h-12 rounded-lg border border-gray-200 object-cover">
                            <div>
                                <h4 class="font-bold text-gray-800 line-clamp-1">{{ $prod->name }}</h4>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-600">{{ $prod->category->name ?? '-' }}</td>
                    <td class="px-6 py-4">
                        <div class="text-gray-800 font-bold">Rp {{ number_format($prod->variants->min('price') ?? 0, 0, ',', '.') }}</div>
                        <div class="text-[10px] text-gray-400">{{ $prod->variants->sum('stock') }} Stok Total</div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div x-data="{ 
                            isActive: {{ $prod->is_active ? 'true' : 'false' }},
                            toggle() {
                                fetch(`/admin/produk/{{ $prod->id }}/toggle-status`, {
                                    method: 'POST',
                                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                                }).then(res => res.json()).then(data => {
                                    if(data.success) this.isActive = data.is_active;
                                });
                            }
                        }" class="flex justify-center">
                            <button @click="toggle" :class="isActive ? 'bg-green-500' : 'bg-gray-300'" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none">
                                <span :class="isActive ? 'translate-x-6' : 'translate-x-1'" class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"></span>
                            </button>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.products.edit', $prod->id) }}" class="w-8 h-8 rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-500 hover:text-white transition flex items-center justify-center">
                                <i class="fa-solid fa-pen text-xs"></i>
                            </a>
                            <form action="{{ route('admin.products.destroy', $prod->id) }}" method="POST" onsubmit="return confirm('Hapus produk permanen?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-500 hover:text-white transition flex items-center justify-center">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-gray-100">
        {{ $products->links('pagination::tailwind') }}
    </div>
</div>
@endsection