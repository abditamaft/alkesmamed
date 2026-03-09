@extends('admin.layouts.app')

@section('content')
<div class="mb-8">
    <a href="{{ route('admin.products.index') }}" class="text-sm font-bold text-gray-500 hover:text-blue-600 transition flex items-center gap-2 mb-4">
        <i class="fa-solid fa-arrow-left"></i> Kembali
    </a>
    <h1 class="text-2xl font-black text-gray-800">Tambah Produk Baru</h1>
</div>

@if($errors->any())
    <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 font-bold text-sm">
        <ul class="list-disc pl-5">
            @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 space-y-6">
            
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Informasi Dasar</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1.5">Nama Produk</label>
                        <input type="text" name="name" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1.5">Deskripsi Produk (Bisa pakai HTML)</label>
                        <textarea name="description" rows="5" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none"></textarea>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100" x-data="{
                variants: [ { id: 1 } ],
                addVariant() { this.variants.push({ id: Date.now() }); },
                removeVariant(id) { 
                    if(this.variants.length > 1) { this.variants = this.variants.filter(v => v.id !== id); }
                    else { alert('Minimal harus ada 1 varian ukuran/tipe!'); }
                }
            }">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-bold text-gray-800">Varian, Harga & Stok</h2>
                    <button type="button" @click="addVariant()" class="bg-blue-50 text-blue-600 px-3 py-1.5 rounded-lg text-sm font-bold hover:bg-blue-100 transition">
                        <i class="fa-solid fa-plus"></i> Tambah Varian
                    </button>
                </div>

                <div class="space-y-4">
                    <template x-for="(variant, index) in variants" :key="variant.id">
                        <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl relative">
                            <button type="button" @click="removeVariant(variant.id)" class="absolute -top-2 -right-2 bg-red-500 text-white w-6 h-6 rounded-full flex items-center justify-center shadow hover:bg-red-600 transition">
                                <i class="fa-solid fa-xmark text-xs"></i>
                            </button>
                            
                            <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                                <div class="col-span-2 md:col-span-1">
                                    <label class="block text-xs font-bold text-gray-600 mb-1">Nama Varian</label>
                                    <input type="text" :name="'variants['+index+'][name]'" placeholder="Misal: Small" required class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-600 mb-1">Harga (Rp)</label>
                                    <input type="number" :name="'variants['+index+'][price]'" placeholder="50000" required class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-600 mb-1">Stok</label>
                                    <input type="number" :name="'variants['+index+'][stock]'" placeholder="10" required class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-600 mb-1">Berat (Gram)</label>
                                    <input type="number" :name="'variants['+index+'][weight]'" placeholder="1000" required class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-600 mb-1">SKU (Opsional)</label>
                                    <input type="text" :name="'variants['+index+'][sku]'" placeholder="KODE-123" class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none focus:border-blue-500">
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

        </div>

        <div class="space-y-6">
            
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h2 class="text-base font-bold text-gray-800 mb-4">Pengaturan</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1.5">Kategori</label>
                        <select name="category_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none font-medium">
                            <option value="">Pilih Kategori...</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <label class="flex items-center gap-2 cursor-pointer mt-4">
                        <input type="checkbox" name="is_active" value="1" checked class="w-5 h-5 text-blue-600 rounded">
                        <span class="text-sm font-bold text-gray-700">Tampilkan Produk Ini</span>
                    </label>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100" x-data="{
                images: [ { id: Date.now(), preview: null } ],
                
                addImage() {
                    if(this.images.length < 5) {
                        this.images.push({ id: Date.now(), preview: null });
                    } else {
                        alert('Maksimal hanya 5 gambar, Bos!');
                    }
                },
                
                removeImage(id) {
                    if(this.images.length > 1) {
                        this.images = this.images.filter(img => img.id !== id);
                    } else {
                        alert('Minimal harus ada 1 gambar untuk Cover!');
                    }
                },
                
                previewFile(event, index) {
                    const file = event.target.files[0];
                    if (file) {
                        this.images[index].preview = URL.createObjectURL(file);
                    } else {
                        this.images[index].preview = null;
                    }
                }
            }">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-base font-bold text-gray-800">Galeri Gambar</h2>
                    <button type="button" @click="addImage()" class="bg-blue-50 text-blue-600 px-3 py-1.5 rounded-lg text-sm font-bold hover:bg-blue-100 transition">
                        <i class="fa-solid fa-plus"></i> Tambah Slot
                    </button>
                </div>

                <div class="space-y-3">
                    <template x-for="(img, index) in images" :key="img.id">
                        <div class="flex items-center gap-4 p-3 border border-gray-200 rounded-xl relative bg-slate-50 transition-all">
                            
                            <div class="w-16 h-16 rounded-lg bg-gray-200 border border-gray-300 flex-shrink-0 overflow-hidden flex items-center justify-center relative shadow-inner">
                                <template x-if="img.preview">
                                    <img :src="img.preview" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!img.preview">
                                    <i class="fa-solid fa-image text-gray-400 text-xl"></i>
                                </template>
                                
                                <div x-show="index === 0" class="absolute bottom-0 inset-x-0 bg-blue-600 text-white text-[9px] font-black text-center py-0.5 tracking-wider">
                                    COVER
                                </div>
                            </div>

                            <div class="flex-1 overflow-hidden">
                                <label class="block text-xs font-bold text-gray-700 mb-1" x-text="index === 0 ? 'Gambar Utama (Wajib)' : 'Gambar Tambahan ' + index"></label>
                                <input type="file" name="images[]" accept="image/*" @change="previewFile($event, index)" :required="index === 0"
                                       class="w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200 cursor-pointer transition">
                            </div>

                            <button type="button" x-show="index > 0" @click="removeImage(img.id)" class="w-8 h-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center hover:bg-red-500 hover:text-white transition flex-shrink-0">
                                <i class="fa-solid fa-trash text-xs"></i>
                            </button>
                            
                        </div>
                    </template>
                </div>
                
                <p class="text-[10px] text-gray-400 mt-4 text-center font-medium">Format: JPG, PNG, WEBP. Maks 2MB/file. Limit 5 Gambar.</p>
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-4 rounded-xl shadow-xl shadow-blue-500/30 transition transform hover:-translate-y-1">
                <i class="fa-solid fa-save mr-2"></i> SIMPAN PRODUK
            </button>

        </div>
    </div>
</form>
@endsection