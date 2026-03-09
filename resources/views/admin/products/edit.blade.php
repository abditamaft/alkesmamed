@extends('admin.layouts.app')

@section('content')
<div class="mb-8 flex justify-between items-center">
    <div>
        <a href="{{ route('admin.products.index') }}" class="text-sm font-bold text-gray-500 hover:text-blue-600 transition flex items-center gap-2 mb-4">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
        <h1 class="text-2xl font-black text-gray-800">Edit Produk: {{ $product->name }}</h1>
    </div>
</div>

<form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Informasi Dasar</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1.5">Nama Produk</label>
                        <input type="text" name="name" value="{{ $product->name }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1.5">Deskripsi Produk</label>
                        <textarea name="description" rows="5" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5">{{ $product->description }}</textarea>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100" x-data="{
                variants: [
                    @foreach($product->variants as $var)
                        { unique: {{ $var->id }}, id: {{ $var->id }}, name: '{{ $var->variant_name }}', price: {{ $var->price }}, stock: {{ $var->stock }}, weight: {{ $var->weight_gram }}, sku: '{{ $var->sku }}' },
                    @endforeach
                ],
                addVariant() { this.variants.push({ unique: Date.now(), id: null, name: '', price: '', stock: '', weight: '', sku: '' }); },
                removeVariant(unique) { 
                    if(this.variants.length > 1) { this.variants = this.variants.filter(v => v.unique !== unique); }
                    else { alert('Minimal 1 varian!'); }
                }
            }">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-bold text-gray-800">Varian, Harga & Stok</h2>
                    <button type="button" @click="addVariant()" class="bg-blue-50 text-blue-600 px-3 py-1.5 rounded-lg text-sm font-bold">
                        <i class="fa-solid fa-plus"></i> Tambah Varian
                    </button>
                </div>
                <div class="space-y-4">
                    <template x-for="(variant, index) in variants" :key="variant.unique">
                        <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl relative">
                            <button type="button" @click="removeVariant(variant.unique)" class="absolute -top-2 -right-2 bg-red-500 text-white w-6 h-6 rounded-full flex justify-center items-center shadow">
                                <i class="fa-solid fa-xmark text-xs"></i>
                            </button>
                            <template x-if="variant.id !== null">
                                <input type="hidden" :name="'variants['+index+'][id]'" :value="variant.id">
                            </template>
                            
                            <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                                <div class="col-span-2 md:col-span-1">
                                    <label class="block text-xs font-bold text-gray-600 mb-1">Nama</label>
                                    <input type="text" :name="'variants['+index+'][name]'" x-model="variant.name" required class="w-full border rounded-lg px-3 py-2 text-sm">
                                </div>
                                <div><label class="block text-xs font-bold">Harga</label><input type="number" :name="'variants['+index+'][price]'" x-model="variant.price" required class="w-full border rounded-lg px-3 py-2 text-sm"></div>
                                <div><label class="block text-xs font-bold">Stok</label><input type="number" :name="'variants['+index+'][stock]'" x-model="variant.stock" required class="w-full border rounded-lg px-3 py-2 text-sm"></div>
                                <div><label class="block text-xs font-bold">Berat (gr)</label><input type="number" :name="'variants['+index+'][weight]'" x-model="variant.weight" required class="w-full border rounded-lg px-3 py-2 text-sm"></div>
                                <div><label class="block text-xs font-bold">SKU</label><input type="text" :name="'variants['+index+'][sku]'" x-model="variant.sku" class="w-full border rounded-lg px-3 py-2 text-sm"></div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h2 class="text-base font-bold text-gray-800 mb-4">Kategori & Status</h2>
                <select name="category_id" required class="w-full bg-slate-50 border rounded-xl px-4 py-2.5 mb-4">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ $product->is_active ? 'checked' : '' }} class="w-5 h-5 text-blue-600 rounded">
                    <span class="text-sm font-bold">Tampilkan Produk Ini</span>
                </label>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100" x-data="{
                images: [
                    @foreach($product->images as $img)
                        { unique: '{{ $img->id }}_old', id: {{ $img->id }}, isOld: true, preview: '{{ asset('images/'.$img->image_path) }}' },
                    @endforeach
                ],
                addSlot() { 
                    if(this.images.length < 5) { this.images.push({ unique: Date.now(), id: null, isOld: false, preview: null }); } 
                    else { alert('Maksimal 5 gambar, Bos!'); } 
                },
                removeSlot(unique) { 
                    if(this.images.length > 1) { this.images = this.images.filter(i => i.unique !== unique); } 
                    else { alert('Minimal harus ada 1 gambar (Cover) yang tersisa!'); } 
                },
                previewFile(event, index) {
                    const file = event.target.files[0];
                    if(file) { 
                        this.images[index].preview = URL.createObjectURL(file); 
                        this.images[index].isOld = false; 
                        this.images[index].id = null; 
                    }
                }
            }">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-base font-bold text-gray-800">Galeri Foto</h2>
                    <button type="button" @click="addSlot()" class="text-blue-600 text-sm font-bold bg-blue-50 px-3 py-1.5 rounded-lg hover:bg-blue-100 transition">
                        <i class="fa-solid fa-plus"></i> Tambah Slot
                    </button>
                </div>

                <input type="hidden" name="cover_is_new" :value="!images[0].isOld ? '1' : '0'">
                <input type="hidden" name="cover_old_id" :value="images[0].isOld ? images[0].id : ''">

                <div class="space-y-4">
                    <template x-for="(img, index) in images" :key="img.unique">
                        <div class="flex flex-col sm:flex-row items-center gap-4 p-4 border border-gray-200 rounded-xl bg-slate-50 relative transition-all hover:shadow-md">
                            
                            <input type="hidden" name="kept_images[]" :value="img.id" x-bind:disabled="!img.isOld || img.id === null">
                            
                            <div class="w-20 h-20 rounded-lg bg-gray-200 border border-gray-300 flex-shrink-0 overflow-hidden relative shadow-inner flex items-center justify-center">
                                <template x-if="img.preview">
                                    <img :src="img.preview" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!img.preview">
                                    <i class="fa-solid fa-image text-gray-400 text-2xl"></i>
                                </template>
                                
                                <div x-show="index === 0" class="absolute bottom-0 inset-x-0 bg-blue-600 text-white text-[10px] font-black text-center py-0.5 tracking-wider z-10">
                                    COVER
                                </div>
                            </div>

                            <div class="flex-1 w-full">
                                <p class="text-xs font-bold text-gray-800 mb-1" x-text="index === 0 ? 'Gambar Utama (Cover)' : 'Gambar Tambahan ' + index"></p>
                                <p class="text-[10px] text-gray-500 mb-2 font-medium" x-text="img.isOld ? 'Tersimpan (Pilih file untuk mengganti)' : 'Slot Baru (Silakan pilih gambar)'"></p>
                                
                                <input type="file" name="new_images[]" accept="image/*" @change="previewFile($event, index)" :required="!img.isOld && !img.preview"
                                       class="w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:font-bold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200 cursor-pointer transition">
                            </div>

                            <button type="button" @click="removeSlot(img.unique)" class="w-8 h-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center hover:bg-red-500 hover:text-white transition flex-shrink-0">
                                <i class="fa-solid fa-trash text-xs"></i>
                            </button>
                            
                        </div>
                    </template>
                </div>
                
                <p class="text-[10px] text-gray-400 mt-4 text-center font-medium">Anda bisa MENGHAPUS atau MENGGANTI gambar manapun. Label COVER akan selalu mengikuti gambar di urutan pertama.</p>
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-4 rounded-xl shadow-xl transition">
                <i class="fa-solid fa-save mr-2"></i> PERBARUI PRODUK
            </button>
        </div>
    </div>
</form>
@endsection