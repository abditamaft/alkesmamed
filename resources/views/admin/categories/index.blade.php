@extends('admin.layouts.app')

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
    <div>
        <h1 class="text-2xl font-black text-gray-800">Kategori Produk</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola semua kategori utama dan ikon untuk produk alat kesehatan Anda.</p>
    </div>
</div>

@if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm font-bold mb-6 flex items-center gap-2">
        <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
    </div>
@endif
@if(session('error') || $errors->any())
    <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl text-sm font-bold mb-6 flex items-center gap-2">
        <i class="fa-solid fa-triangle-exclamation"></i> {{ session('error') ?? $errors->first() }}
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <div class="lg:col-span-1">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 sticky top-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4 border-b border-gray-100 pb-3">Tambah Kategori Baru</h2>
            
            <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">Nama Kategori</label>
                    <input type="text" name="name" required placeholder="Contoh: Alat Bantu Jalan"
                           class="w-full bg-slate-50 border border-slate-200 text-gray-800 rounded-xl px-4 py-2.5 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition shadow-sm text-sm font-medium">
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">Gambar / Ikon (Opsional)</label>
                    <input type="file" name="image" accept="image/*"
                           class="w-full bg-slate-50 border border-slate-200 text-gray-800 rounded-xl px-4 py-2 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition">
                    <p class="text-[10px] text-gray-400 mt-1">Format: JPG, PNG, WEBP. Maks 2MB.</p>
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-xl shadow-md transition flex items-center justify-center gap-2 mt-2">
                    <i class="fa-solid fa-plus"></i> Simpan Kategori
                </button>
            </form>
        </div>
    </div>

    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                        <th class="px-6 py-4 font-bold">Kategori</th>
                        <th class="px-6 py-4 font-bold">Slug URL</th>
                        <th class="px-6 py-4 font-bold text-center">Jml Produk</th>
                        <th class="px-6 py-4 font-bold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse($categories as $cat)
                    <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-gray-100 border border-gray-200 overflow-hidden flex-shrink-0 flex items-center justify-center">
                                    @if($cat->image)
                                        <img src="{{ asset('images/' . $cat->image) }}" class="w-full h-full object-cover">
                                    @else
                                        <i class="fa-solid fa-image text-gray-300"></i>
                                    @endif
                                </div>
                                <span class="font-bold text-gray-800">{{ $cat->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-500">{{ $cat->slug }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-xs font-bold">{{ $cat->products_count }} item</span>
                        </td>
                        <td class="px-6 py-4 text-right flex justify-end gap-2">
                            <a href="{{ route('admin.categories.edit', $cat->id) }}" class="w-8 h-8 rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-500 hover:text-white transition flex items-center justify-center">
                                <i class="fa-solid fa-pen text-xs"></i>
                            </a>
                            <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori beserta gambarnya?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-500 hover:text-white transition flex items-center justify-center">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-gray-400 font-medium">
                            <i class="fa-solid fa-folder-open text-3xl mb-3 block"></i>
                            Belum ada kategori yang ditambahkan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-50">
            {{ $categories->links('pagination::tailwind') }}
        </div>
    </div>
</div>
@endsection