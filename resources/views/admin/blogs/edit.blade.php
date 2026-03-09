@extends('admin.layouts.app')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.blogs.index') }}" class="text-sm font-bold text-gray-500 hover:text-blue-600 transition flex items-center gap-2 mb-4">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Artikel
    </a>
    <h1 class="text-2xl font-black text-gray-800">Edit Artikel Blog</h1>
</div>

@if($errors->any()) <div class="bg-red-50 text-red-700 p-4 rounded-xl mb-6 font-bold border border-red-200">{{ $errors->first() }}</div> @endif

<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<form action="{{ route('admin.blogs.update', $post->id) }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    @csrf
    @method('PUT') <div class="lg:col-span-2 space-y-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-2">Judul Artikel</label>
                <input type="text" name="title" value="{{ old('title', $post->title) }}" required placeholder="Contoh: Manfaat Menggunakan Masker Medis..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 outline-none focus:border-blue-500 font-bold text-gray-800">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2 flex justify-between">
                    Isi Konten Fleksibel
                    <span class="text-xs font-normal text-gray-400">Teks tebal, sisip gambar, dll.</span>
                </label>
                <textarea name="content" id="summernote" required>{!! old('content', $post->content) !!}</textarea>
            </div>
        </div>
    </div>

    <div class="lg:col-span-1 space-y-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 sticky top-6">
            
            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Kategori</label>
                <select name="blog_category_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 outline-none focus:border-blue-500 font-medium">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $post->blog_category_id == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-2">Gambar Cover Utama</label>
                
                <div class="mb-3 rounded-xl overflow-hidden border border-gray-200 bg-gray-50 h-40">
                    <img src="{{ asset('images/' . $post->image_path) }}" class="w-full h-full object-cover">
                </div>

                <div class="border-2 border-dashed border-gray-300 rounded-xl p-4 text-center hover:bg-gray-50 transition relative" x-data="{ imageUrl: null }">
                    <input type="file" name="image" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" @change="imageUrl = URL.createObjectURL($event.target.files[0])">
                    
                    <div x-show="!imageUrl" class="py-2">
                        <i class="fa-solid fa-cloud-arrow-up text-2xl text-gray-400 mb-1"></i>
                        <p class="text-[11px] font-bold text-gray-600">Ganti Foto Cover?</p>
                        <p class="text-[9px] text-gray-400 mt-1">Abaikan jika tidak ingin mengubah</p>
                    </div>
                    <img x-show="imageUrl" :src="imageUrl" class="w-full h-24 object-cover rounded-lg shadow-sm mt-2" style="display: none;">
                </div>
            </div>

            <div class="mb-6 bg-blue-50 p-4 rounded-xl border border-blue-100 flex items-center justify-between" x-data="{ isPublished: {{ $post->is_published ? 'true' : 'false' }} }">
                <span class="text-sm font-bold text-blue-800">Status Publish</span>
                
                <input type="hidden" name="is_published" value="0">
                <input type="checkbox" name="is_published" value="1" x-model="isPublished" style="display: none;">
                
                <button type="button" @click="isPublished = !isPublished" 
                    :class="isPublished ? 'bg-blue-600' : 'bg-gray-300'" 
                    class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none shadow-inner">
                    <span :class="isPublished ? 'translate-x-6' : 'translate-x-1'" 
                          class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-200 ease-in-out shadow-sm">
                    </span>
                </button>
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition shadow-lg shadow-blue-500/30 flex justify-center items-center gap-2">
                <i class="fa-solid fa-save"></i> Perbarui Artikel
            </button>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        $('#summernote').summernote({
            placeholder: 'Mulai ketik paragraf, tambahkan Subjudul, atau sisipkan gambar di tengah teks...',
            tabsize: 2,
            height: 400,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
    });
</script>
@endsection