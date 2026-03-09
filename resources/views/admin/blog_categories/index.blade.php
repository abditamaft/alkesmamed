@extends('admin.layouts.app')

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-black text-gray-800">Kategori Artikel / Blog</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola topik pembahasan untuk konten blog Anda.</p>
    </div>
</div>

@if(session('success')) <div class="bg-green-50 text-green-700 p-4 rounded-xl mb-4 font-bold border border-green-200">{{ session('success') }}</div> @endif
@if(session('error')) <div class="bg-red-50 text-red-700 p-4 rounded-xl mb-4 font-bold border border-red-200">{{ session('error') }}</div> @endif
@if($errors->any()) <div class="bg-red-50 text-red-700 p-4 rounded-xl mb-4 font-bold border border-red-200">{{ $errors->first() }}</div> @endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <div class="lg:col-span-1">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 sticky top-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4 border-b border-gray-100 pb-3">Tambah Kategori</h2>
            <form action="{{ route('admin.blog_categories.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">Nama Kategori</label>
                    <input type="text" name="name" required placeholder="Contoh: Tips Kesehatan" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 outline-none focus:border-blue-500 focus:ring-1">
                </div>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-xl transition shadow-md">
                    <i class="fa-solid fa-plus mr-1"></i> Simpan Kategori
                </button>
            </form>
        </div>
    </div>

    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                    <th class="px-6 py-4 font-bold">Nama Kategori Blog</th>
                    <th class="px-6 py-4 font-bold text-center">Jumlah Artikel</th>
                    <th class="px-6 py-4 font-bold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @forelse($categories as $cat)
                <tr class="border-b border-gray-50 hover:bg-slate-50 transition" x-data="{ editMode: false }">
                    <td class="px-6 py-4">
                        <div x-show="!editMode">
                            <span class="font-bold text-gray-800">{{ $cat->name }}</span>
                            <span class="block text-[10px] text-gray-400 mt-0.5">/blog?kategori={{ $cat->slug }}</span>
                        </div>
                        <form x-show="editMode" action="{{ route('admin.blog_categories.update', $cat->id) }}" method="POST" class="flex gap-2" style="display: none;">
                            @csrf @method('PUT')
                            <input type="text" name="name" value="{{ $cat->name }}" required class="border border-gray-300 rounded px-2 py-1 text-sm w-full">
                            <button type="submit" class="bg-green-500 text-white px-2 py-1 rounded text-xs font-bold">OK</button>
                            <button type="button" @click="editMode = false" class="bg-gray-300 text-gray-700 px-2 py-1 rounded text-xs font-bold">Batal</button>
                        </form>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-xs font-bold">{{ $cat->posts_count }} Artikel</span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2" x-show="!editMode">
                            <button @click="editMode = true" class="w-8 h-8 rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-500 hover:text-white transition flex justify-center items-center"><i class="fa-solid fa-pen text-xs"></i></button>
                            <form action="{{ route('admin.blog_categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Hapus kategori blog ini?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-500 hover:text-white transition flex justify-center items-center"><i class="fa-solid fa-trash text-xs"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="3" class="px-6 py-10 text-center text-gray-400 font-medium">Belum ada kategori blog.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection