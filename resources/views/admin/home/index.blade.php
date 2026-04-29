@extends('admin.layouts.app')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-black text-gray-800">Manajemen Beranda</h1>
    <p class="text-sm text-gray-500 font-medium">Kelola semua konten visual halaman depan dalam satu tempat.</p>
</div>

@if(session('success'))
<div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 flex items-center gap-3">
    <i class="fa-solid fa-circle-check"></i>
    <span class="font-bold text-sm">{{ session('success') }}</span>
</div>
@endif

<!-- TAMBAHKAN BLOK ERROR INI UNTUK MELIHAT JIKA ADA VALIDASI YANG GAGAL -->
@if($errors->any())
<div class="bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6">
    <ul class="list-disc list-inside text-sm font-bold">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<!-- UBAH x-data AGAR BISA MEMBACA SESSION ATAU OLD REQUEST -->
<div x-data="{ tab: '{{ session('tab', old('tab', 'banner')) }}' }" class="space-y-6">
    
    <div class="flex gap-2 p-1.5 bg-gray-200/50 w-max rounded-xl border border-gray-200 overflow-hidden">
        <button @click="tab = 'banner'" :class="tab === 'banner' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500 hover:text-gray-700'" class="px-5 py-2 rounded-lg text-sm font-bold transition flex items-center gap-2">
            <i class="fa-solid fa-images"></i> Hero Banner
        </button>
        <button @click="tab = 'flash'" :class="tab === 'flash' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500 hover:text-gray-700'" class="px-5 py-2 rounded-lg text-sm font-bold transition flex items-center gap-2">
            <i class="fa-solid fa-bolt"></i> Flash Sale
        </button>
        <button @click="tab = 'about'" :class="tab === 'about' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500 hover:text-gray-700'" class="px-5 py-2 rounded-lg text-sm font-bold transition flex items-center gap-2">
            <i class="fa-solid fa-building"></i> Tentang Kami
        </button>
    </div>

    <div x-show="tab === 'banner'" x-cloak x-transition class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
        <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-100">
            <h2 class="text-lg font-bold text-gray-800">Carousel Banner Utama</h2>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-1 bg-slate-50 p-5 rounded-xl border border-dashed border-slate-300">
                <h3 class="text-sm font-bold text-gray-700 mb-4">Tambah Banner Baru</h3>
                <form action="{{ route('admin.home.storeBanner') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Upload Gambar</label>
                        <input type="file" name="image_path" required class="w-full text-xs file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200 cursor-pointer">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Link URL Tombol (Opsional)</label>
                        <input type="text" name="link_url" placeholder="/produk/slug-produk" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-blue-500">
                        <p class="text-[10px] text-gray-400 mt-1">Kosongkan jika tombol Beli Sekarang hanya mengarah ke halaman Produk umum.</p>
                    </div>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-lg transition text-sm">Upload Banner</button>
                </form>
            </div>

            <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse($banners as $b)
                    <div class="relative rounded-xl overflow-hidden group border border-gray-100 shadow-sm">
                        <img src="{{ asset('images/'.$b->image_path) }}" class="w-full h-40 object-cover">
                        <div class="p-3 bg-white border-t border-gray-100 flex justify-between items-center">
                            <span class="text-xs text-gray-500 truncate max-w-[150px]">{{ $b->link_url ?? 'Tidak ada link spesifik' }}</span>
                            <form action="{{ route('admin.home.destroyBanner', $b->id) }}" method="POST" onsubmit="return confirm('Yakin hapus banner ini?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 bg-red-50 p-2 rounded-lg transition"><i class="fa-solid fa-trash text-xs"></i></button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-2 flex flex-col items-center justify-center p-10 bg-gray-50 rounded-xl border border-gray-100">
                        <i class="fa-solid fa-images text-4xl text-gray-300 mb-3"></i>
                        <p class="text-sm font-bold text-gray-400">Belum ada banner promosi.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div x-show="tab === 'flash'" x-cloak x-transition class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Pengaturan Flash Sale</h2>
        <div class="bg-blue-50 p-4 rounded-xl mb-6 border border-blue-100 flex gap-3">
            <i class="fa-solid fa-circle-info text-blue-500 mt-0.5"></i>
            <p class="text-sm text-blue-800 font-medium leading-relaxed">
                Produk Flash Sale diambil secara otomatis dari produk yang dicentang <strong>"Jadikan Flash Sale"</strong> di menu Katalog Produk. Jika Anda ingin menambah atau menghapus produk di daftar ini, silakan Edit Produk tersebut.
            </p>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-600 font-bold border-b border-gray-100">
                    <tr>
                        <th class="px-4 py-3">Nama Produk</th>
                        <th class="px-4 py-3">Harga Coret (Asli)</th>
                        <th class="px-4 py-3">Harga Diskon (Flash)</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($flashSaleProducts as $fs)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-4 py-4 font-bold text-gray-800 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-white border flex items-center justify-center">
                                    <img src="{{ asset('images/' . ($fs->mainImage->image_path ?? 'default.jpg')) }}" class="w-8 h-8 object-contain">
                                </div>
                                {{ $fs->name }}
                            </td>
                            <td class="px-4 py-4 text-gray-400 line-through">Rp{{ number_format($fs->variants->first()->old_price ?? 0, 0, ',', '.') }}</td>
                            <td class="px-4 py-4 text-blue-600 font-black">Rp{{ number_format($fs->variants->first()->price ?? 0, 0, ',', '.') }}</td>
                            <td class="px-4 py-4 text-center">
                                <a href="/admin/products/{{ $fs->id }}/edit" class="bg-white border border-gray-200 text-blue-600 font-bold hover:bg-blue-50 px-3 py-1.5 rounded-lg text-xs transition">Edit Harga/Status</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-400 font-medium">Belum ada produk yang masuk daftar Flash Sale.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div x-show="tab === 'about'" x-cloak x-transition class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
        <h2 class="text-lg font-bold text-gray-800 mb-6 pb-4 border-b border-gray-100">Visual Tentang Kami (Style Zig-Zag)</h2>
        
        <form action="{{ route('admin.home.updateAbout') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            <input type="hidden" name="tab" value="about">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 bg-slate-50 p-6 rounded-2xl border border-gray-100">
                
                <div class="space-y-6">
                    <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm relative">
                        <div class="absolute -top-3 -left-3 w-8 h-8 bg-blue-600 text-white font-black rounded-full flex items-center justify-center border-4 border-slate-50">1</div>
                        <label class="block text-xs font-black text-gray-700 uppercase mb-2">Teks Paragraf Atas (Kiri)</label>
                        <textarea name="excerpt" rows="4" class="w-full rounded-lg border-gray-200 text-sm focus:ring-1 focus:ring-blue-500" placeholder="Visi perusahaan...">{{ $aboutUs->excerpt ?? '' }}</textarea>
                    </div>

                    <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm relative">
                        <div class="absolute -top-3 -left-3 w-8 h-8 bg-blue-600 text-white font-black rounded-full flex items-center justify-center border-4 border-slate-50">2</div>
                        <label class="block text-xs font-black text-gray-700 uppercase mb-2">Gambar Bawah (Kiri)</label>
                        <input type="file" name="image_1" class="w-full text-xs file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-100 file:text-blue-700">
                        @if($aboutUs && $aboutUs->image_1)
                            <div class="mt-3 p-2 border border-dashed rounded-lg bg-gray-50">
                                <img src="{{ asset('images/'.$aboutUs->image_1) }}" class="h-24 w-full object-cover rounded-md">
                            </div>
                        @endif
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm relative">
                        <div class="absolute -top-3 -left-3 w-8 h-8 bg-blue-600 text-white font-black rounded-full flex items-center justify-center border-4 border-slate-50">3</div>
                        <label class="block text-xs font-black text-gray-700 uppercase mb-2">Gambar Atas (Kanan)</label>
                        <input type="file" name="image_2" class="w-full text-xs file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-100 file:text-blue-700">
                        @if($aboutUs && $aboutUs->image_2)
                            <div class="mt-3 p-2 border border-dashed rounded-lg bg-gray-50">
                                <img src="{{ asset('images/'.$aboutUs->image_2) }}" class="h-24 w-full object-cover rounded-md">
                            </div>
                        @endif
                    </div>

                    <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm relative">
                        <div class="absolute -top-3 -left-3 w-8 h-8 bg-blue-600 text-white font-black rounded-full flex items-center justify-center border-4 border-slate-50">4</div>
                        <label class="block text-xs font-black text-gray-700 uppercase mb-2">Teks Paragraf Bawah (Kanan)</label>
                        <textarea name="content" rows="4" class="w-full rounded-lg border-gray-200 text-sm focus:ring-1 focus:ring-blue-500" placeholder="Misi perusahaan...">{{ $aboutUs->content ?? '' }}</textarea>
                    </div>
                </div>
            </div>
            
            <button type="submit" class="w-full bg-blue-600 text-white font-black py-4 rounded-xl shadow-lg shadow-blue-500/30 hover:bg-blue-700 hover:scale-[1.01] transition transform">
                <i class="fa-solid fa-save mr-2"></i> SIMPAN DESAIN ZIG-ZAG TENTANG KAMI
            </button>
        </form>
    </div>

</div>
@endsection