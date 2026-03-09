@extends('admin.layouts.app')

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-start md:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-black text-gray-800">Wilayah & Ongkos Kirim</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola daftar provinsi dan ongkos kirim per kotanya.</p>

        @if(request('search'))
        <div class="mt-3 inline-flex items-center gap-3 bg-blue-50 px-3 py-1.5 rounded-full border border-blue-100">
            <span class="text-xs font-bold text-blue-800">Mencari: "{{ request('search') }}"</span>
            <a href="{{ route('admin.shipping.index') }}" class="text-blue-500 hover:text-red-500 transition" title="Reset Pencarian">
                <i class="fa-solid fa-circle-xmark text-sm"></i>
            </a>
        </div>
        @endif
    </div>

    <div class="relative w-full md:w-80" x-data="{
        query: '{{ request('search') }}',
        results: [],
        loading: false,
        searchData() {
            if(this.query.length < 2) { this.results = []; return; }
            this.loading = true;
            fetch(`/admin/ongkir/search?q=${this.query}`)
                .then(res => res.json())
                .then(data => { this.results = data; this.loading = false; })
                .catch(() => { this.loading = false; });
        }
    }">
        
        <form action="{{ route('admin.shipping.index') }}" method="GET" class="relative">
            <button type="submit" class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-blue-600 transition z-10">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
            
            <input type="text" name="search" x-model="query" @input.debounce.300ms="searchData" placeholder="Cari Provinsi/Kota (Enter)..." autocomplete="off"
                   class="w-full bg-white border border-gray-200 rounded-xl pl-11 pr-10 py-2.5 text-sm focus:border-blue-500 focus:ring-1 outline-none shadow-sm transition">
            
            <div class="absolute right-4 top-1/2 -translate-y-1/2 flex items-center">
                <i x-show="loading" class="fa-solid fa-spinner fa-spin text-blue-500 text-sm" style="display: none;"></i>
            </div>
        </form>
        
        <div x-show="results.length > 0" @click.outside="results = []" style="display: none;" class="absolute top-full left-0 w-full mt-2 bg-white rounded-xl shadow-2xl border border-gray-100 z-50 overflow-hidden max-h-72 overflow-y-auto custom-scrollbar">
            <template x-for="item in results" :key="item.id">
                <a :href="`/admin/ongkir/${item.province_id}`" class="flex items-center gap-3 p-3 border-b border-gray-50 hover:bg-blue-50 transition group">
                    <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-500 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition">
                        <i class="fa-solid fa-location-dot text-xs"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-800 group-hover:text-blue-600 transition" x-text="item.type + ' ' + item.name"></p>
                        <p class="text-[10px] text-gray-400 font-medium" x-text="'Provinsi: ' + (item.province ? item.province.name : '-')"></p>
                    </div>
                </a>
            </template>
        </div>
        
        <div x-show="query.length >= 2 && results.length === 0 && !loading" style="display: none;" class="absolute top-full left-0 w-full mt-2 bg-white rounded-xl shadow-2xl border border-gray-100 z-50 p-4 text-center">
            <i class="fa-solid fa-box-open text-gray-300 text-2xl mb-2 block"></i>
            <p class="text-xs text-gray-500 font-medium">Kota/Provinsi tidak ditemukan.</p>
        </div>
    </div>
</div>

@if(session('success')) <div class="bg-green-50 text-green-700 p-4 rounded-xl mb-4 font-bold border border-green-200">{{ session('success') }}</div> @endif
@if(session('error')) <div class="bg-red-50 text-red-700 p-4 rounded-xl mb-4 font-bold border border-red-200">{{ session('error') }}</div> @endif
@if($errors->any()) <div class="bg-red-50 text-red-700 p-4 rounded-xl mb-4 font-bold border border-red-200">{{ $errors->first() }}</div> @endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <div class="lg:col-span-1">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 sticky top-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4 border-b border-gray-100 pb-3">Tambah Provinsi</h2>
            <form action="{{ route('admin.shipping.storeProvince') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">Nama Provinsi</label>
                    <input type="text" name="name" required placeholder="Contoh: Jawa Timur" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 outline-none focus:border-blue-500 focus:ring-1">
                </div>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-xl transition shadow-md">
                    <i class="fa-solid fa-plus mr-1"></i> Simpan Provinsi
                </button>
            </form>
        </div>
    </div>

    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                    <th class="px-6 py-4 font-bold">Nama Provinsi</th>
                    <th class="px-6 py-4 font-bold text-center">Jumlah Kota</th>
                    <th class="px-6 py-4 font-bold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @forelse($provinces as $prov)
                <tr class="border-b border-gray-50 hover:bg-slate-50 transition" x-data="{ editMode: false }">
                    <td class="px-6 py-4">
                        <div x-show="!editMode" class="font-bold text-gray-800">{{ $prov->name }}</div>
                        <form x-show="editMode" action="{{ route('admin.shipping.updateProvince', $prov->id) }}" method="POST" class="flex gap-2" style="display: none;">
                            @csrf @method('PUT')
                            <input type="text" name="name" value="{{ $prov->name }}" required class="border border-gray-300 rounded px-2 py-1 text-sm w-full">
                            <button type="submit" class="bg-green-500 text-white px-2 py-1 rounded text-xs font-bold">OK</button>
                            <button type="button" @click="editMode = false" class="bg-gray-300 text-gray-700 px-2 py-1 rounded text-xs font-bold">Batal</button>
                        </form>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-xs font-bold">{{ $prov->cities_count }} Kota</span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2" x-show="!editMode">
                            <a href="{{ route('admin.shipping.show', $prov->id) }}" class="bg-indigo-50 text-indigo-600 px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-indigo-600 hover:text-white transition">
                                Atur Kota & Ongkir <i class="fa-solid fa-arrow-right ml-1"></i>
                            </a>
                            <button @click="editMode = true" class="w-8 h-8 rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-500 hover:text-white transition flex justify-center items-center"><i class="fa-solid fa-pen text-xs"></i></button>
                            <form action="{{ route('admin.shipping.destroyProvince', $prov->id) }}" method="POST" onsubmit="return confirm('Hapus provinsi ini?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-500 hover:text-white transition flex justify-center items-center"><i class="fa-solid fa-trash text-xs"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="3" class="px-6 py-10 text-center text-gray-400 font-medium">Belum ada data provinsi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection