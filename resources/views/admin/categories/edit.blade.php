@extends('admin.layouts.app')

@section('content')
<div class="mb-8">
    <a href="{{ route('admin.categories.index') }}" class="text-sm font-bold text-gray-500 hover:text-blue-600 transition flex items-center gap-2 mb-4">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Kategori
    </a>
    <h1 class="text-2xl font-black text-gray-800">Edit Kategori</h1>
    <p class="text-sm text-gray-500 mt-1">Perbarui nama atau gambar kategori <strong>{{ $category->name }}</strong>.</p>
</div>

<div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 max-w-2xl">
    
    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl text-sm font-bold mb-6">
            <i class="fa-solid fa-triangle-exclamation mr-2"></i> {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Nama Kategori</label>
            <input type="text" name="name" value="{{ old('name', $category->name) }}" required
                   class="w-full bg-slate-50 border border-slate-200 text-gray-800 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition shadow-sm font-medium">
        </div>
        
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Gambar / Ikon Saat Ini</label>
            @if($category->image)
                <div class="w-32 h-32 rounded-xl border-2 border-dashed border-gray-300 overflow-hidden mb-3 p-1">
                    <img src="{{ asset('images/' . $category->image) }}" class="w-full h-full object-cover rounded-lg">
                </div>
            @else
                <p class="text-sm text-gray-400 mb-3 italic">Belum ada gambar.</p>
            @endif
            
            <label class="block text-sm font-bold text-gray-700 mb-1.5">Ganti Gambar (Biarkan kosong jika tidak ingin ganti)</label>
            <input type="file" name="image" accept="image/*"
                   class="w-full bg-slate-50 border border-slate-200 text-gray-800 rounded-xl px-4 py-2.5 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition">
        </div>

        <div class="pt-4 border-t border-gray-100 flex gap-3">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl shadow-md transition flex items-center justify-center gap-2">
                <i class="fa-solid fa-save"></i> Simpan Perubahan
            </button>
            <a href="{{ route('admin.categories.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-3 px-6 rounded-xl transition">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection