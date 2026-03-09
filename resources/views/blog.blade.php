@extends('layouts.app')

@section('content')
<div class="bg-white min-h-screen py-10 px-4 md:px-10" x-data="{ openFilter: false }">

    <nav class="text-sm text-gray-400 mb-8 max-w-7xl mx-auto page-enter">
        <a href="/" class="hover:text-blue-600">Beranda</a> / 
        <span class="text-gray-800 font-medium">Blog</span>
    </nav>

    <div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-10">
        
        <div x-show="openFilter" x-transition.opacity class="fixed inset-0 bg-black/60 z-[60] lg:hidden" @click="openFilter = false" x-cloak></div>

        <aside :class="openFilter ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
               class="fixed lg:static top-0 left-0 h-screen lg:h-auto w-[280px] lg:w-1/4 bg-white lg:bg-transparent z-[70] lg:z-auto transition-transform duration-300 ease-in-out flex-shrink-0 shadow-2xl lg:shadow-none">
            
            <div class="flex justify-between items-center p-5 lg:hidden border-b border-gray-100 bg-white">
                <h2 class="font-bold text-gray-800 text-lg"><i class="fa-solid fa-magnifying-glass mr-2 text-blue-500"></i> Cari & Kategori</h2>
                <button @click="openFilter = false" class="text-gray-400 hover:text-red-500 w-8 h-8 flex items-center justify-center rounded-full bg-gray-50 hover:bg-red-50 transition">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <div class="p-5 lg:p-0 space-y-8 sticky top-24 h-[calc(100vh-4rem)] lg:h-[calc(100vh-6rem)] overflow-y-auto custom-scrollbar lg:pr-3 pb-10">
                
                <div class="bg-[#f8f8f8] p-6 rounded-xl border border-gray-100 relative" 
                     x-data="{ 
                        query: '{{ request('cari') }}', 
                        results: [], 
                        isTyping: false,
                        searchData() {
                            if(this.query.length > 1) { // Minimal 2 huruf
                                this.isTyping = true;
                                fetch('/api/blog/search?q=' + this.query)
                                    .then(res => res.json())
                                    .then(data => {
                                        this.results = data;
                                        this.isTyping = false;
                                    })
                                    .catch(err => {
                                        this.isTyping = false; 
                                        console.error('Pencarian gagal:', err);
                                    });
                            } else {
                                this.results = [];
                                this.isTyping = false;
                            }
                        } 
                     }">
                    <h3 class="text-lg font-bold mb-4 text-gray-800">Cari Artikel</h3>
                    
                    <form action="{{ route('blog.index') }}" method="GET" class="relative">
                        <div class="relative flex items-center">
                            <input type="text" name="cari" x-model="query" @input.debounce.300ms="searchData" placeholder="Ketik lalu tekan Enter..." autocomplete="off"
                                   class="w-full bg-white border border-gray-200 rounded-full px-5 pr-12 h-12 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 shadow-sm transition">
                            
                            <div x-show="isTyping" class="absolute right-12 top-1/2 -translate-y-1/2 flex items-center" style="display: none;">
                                <i class="fa-solid fa-spinner fa-spin text-blue-500 text-sm"></i>
                            </div>

                            <button type="submit" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-blue-600 flex items-center p-1">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </button>
                        </div>
                    </form>

                    <div x-show="results.length > 0" @click.outside="results = []" style="display: none;" class="absolute z-[99] w-full left-0 mt-2 bg-white rounded-xl shadow-2xl border border-gray-100 overflow-hidden max-h-64 overflow-y-auto custom-scrollbar">
                        <template x-for="item in results" :key="item.id">
                            <a :href="'/blog/' + item.id" class="flex items-center gap-3 p-3 hover:bg-blue-50 border-b border-gray-50 transition group">
                                <div class="w-12 h-12 rounded overflow-hidden flex-shrink-0 border border-gray-100">
                                    <img :src="item.image_path ? '/images/' + item.image_path : '/images/default.jpg'" class="w-full h-full object-cover">
                                </div>
                                <span class="text-xs font-bold text-gray-700 group-hover:text-blue-600 line-clamp-2 leading-snug" x-text="item.title"></span>
                            </a>
                        </template>
                    </div>

                    <div x-show="query.length >= 2 && results.length === 0 && !isTyping" style="display: none;" class="absolute z-[99] w-full left-0 mt-2 bg-white rounded-xl shadow-lg border border-gray-100 p-4 text-center">
                        <span class="text-xs font-bold text-gray-500">Artikel tidak ditemukan.</span>
                    </div>

                </div>

                <div class="bg-[#f8f8f8] p-6 rounded-xl border border-gray-100">
                    <h3 class="text-lg font-bold mb-4 text-gray-800 flex justify-between items-center">
                        Kategori <a href="{{ route('blog.index') }}" class="text-[10px] text-blue-500 hover:underline font-normal">Reset</a>
                    </h3>
                    <ul class="space-y-3 text-sm font-medium">
                        @foreach($categories as $cat)
                        <li>
                            <a href="{{ route('blog.index', ['kategori' => $cat->slug]) }}" 
                               class="flex justify-between items-center group transition {{ request('kategori') == $cat->slug ? 'text-blue-600' : 'text-gray-500 hover:text-blue-600' }}">
                                <span class="flex items-center gap-2">
                                    <i class="fa-solid fa-chevron-right text-[10px] opacity-0 group-hover:opacity-100 transition-opacity {{ request('kategori') == $cat->slug ? 'opacity-100' : '' }}"></i>
                                    {{ $cat->name }}
                                </span>
                                <span class="bg-white border border-gray-200 text-[10px] px-2 py-0.5 rounded-full text-gray-400 group-hover:border-blue-200 group-hover:text-blue-500 transition">
                                    {{ $cat->posts_count }}
                                </span>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <div class="bg-[#f8f8f8] p-6 rounded-xl border border-gray-100">
                    <h3 class="text-lg font-bold mb-6 text-gray-800">Sedang Trending</h3>
                    
                    <div class="space-y-5">
                        @foreach($popular_posts as $index => $pop)
                        
                        @php
                            $ringColor = 'ring-gray-100'; $badgeColor = 'bg-gray-500'; $badgeText = 'text-white';
                            if($index == 0) { $ringColor = 'ring-yellow-400 ring-offset-2'; $badgeColor = 'bg-gradient-to-r from-yellow-400 to-yellow-600'; $badgeText = 'text-white'; } // Emas
                            elseif($index == 1) { $ringColor = 'ring-gray-300 ring-offset-2'; $badgeColor = 'bg-gradient-to-r from-gray-300 to-gray-500'; $badgeText = 'text-white'; } // Perak
                            elseif($index == 2) { $ringColor = 'ring-amber-700 ring-offset-2'; $badgeColor = 'bg-gradient-to-r from-amber-600 to-amber-800'; $badgeText = 'text-white'; } // Perunggu
                        @endphp

                        <a href="{{ route('blog.show', $pop->id) }}" class="flex gap-4 group cursor-pointer relative items-center">
                            <div class="w-16 h-16 rounded-full overflow-hidden flex-shrink-0 {{ $ringColor }} transition-all duration-300 group-hover:scale-105">
                                <img src="{{ $pop->image_path ? asset('images/' . $pop->image_path) : asset('images/default.jpg') }}" class="w-full h-full object-cover">
                            </div>
                            
                            <div class="absolute -left-2 -top-1 w-6 h-6 rounded-full {{ $badgeColor }} {{ $badgeText }} flex items-center justify-center text-[10px] font-black border-2 border-white shadow-md z-10">
                                #{{ $index + 1 }}
                            </div>

                            <div>
                                <h4 class="text-xs font-bold text-gray-800 group-hover:text-blue-600 leading-snug line-clamp-2 transition">
                                    {{ $pop->title }}
                                </h4>
                                <div class="flex items-center gap-2 mt-1.5 text-[10px] text-gray-400 font-medium">
                                    <span><i class="fa-solid fa-eye text-blue-400"></i> {{ number_format($pop->views, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </aside>

        <div class="w-full lg:w-3/4">
            
            <div class="lg:hidden mb-6 page-enter">
                <button @click="openFilter = true" class="w-full bg-blue-50 text-blue-600 border border-blue-200 font-bold py-3.5 rounded-xl flex justify-center items-center gap-2 hover:bg-blue-100 transition shadow-sm">
                    <i class="fa-solid fa-magnifying-glass"></i> Cari & Kategori Artikel
                </button>
            </div>

            @if(request('kategori') || request('cari'))
                <div class="mb-6 flex justify-between items-center bg-blue-50 p-4 rounded-xl border border-blue-100 page-enter">
                    <p class="text-sm text-blue-800 font-medium">
                        Menampilkan hasil untuk: 
                        <strong>{{ request('kategori') ? 'Kategori ' . request('kategori') : '' }}</strong>
                        <strong>{{ request('cari') ? 'Pencarian "' . request('cari') . '"' : '' }}</strong>
                    </p>
                    <a href="{{ route('blog.index') }}" class="text-xs bg-white text-blue-600 px-3 py-1.5 rounded-full font-bold hover:bg-blue-600 hover:text-white transition">Hapus Filter</a>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @forelse($posts as $index => $article)
                <a href="{{ route('blog.show', $article->id) }}" 
                   x-data="{ shown: false }" 
                   x-intersect.margin.10%="shown = true"
                   :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-20'"
                   class="bg-white border border-gray-100 p-4 rounded-2xl group cursor-pointer transition-all duration-700 ease-out hover:shadow-xl hover:border-blue-100 flex flex-col h-full"
                   style="transition-delay: {{ ($index % 2) * 150 }}ms">
                    
                    <div class="rounded-xl overflow-hidden relative h-56 md:h-64 mb-5">
                        <img src="{{ $article->image_path ? asset('images/' . $article->image_path) : asset('images/default.jpg') }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                        <span class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm text-blue-600 text-[10px] font-black px-3 py-1.5 rounded-lg uppercase tracking-wider shadow-sm">
                            {{ $article->category->name ?? 'Uncategorized' }}
                        </span>
                    </div>

                    <div class="flex items-center gap-4 text-xs font-bold text-gray-400 mb-3">
                        <div class="flex items-center gap-1.5 text-blue-500 bg-blue-50 px-2 py-1 rounded">
                            <i class="fa-regular fa-calendar"></i> {{ $article->updated_at->format('d M Y') }}
                        </div>
                        <div class="flex items-center gap-1.5">
                            <i class="fa-solid fa-fire text-orange-400"></i> {{ number_format($article->views, 0, ',', '.') }} Dilihat
                        </div>
                    </div>

                    <h2 class="text-xl font-extrabold text-gray-800 mb-4 group-hover:text-blue-600 transition leading-snug line-clamp-2">
                        {{ $article->title }}
                    </h2>

                    <div class="inline-flex items-center justify-between w-full text-sm font-bold text-gray-400 mt-auto pt-4 border-t border-gray-50">
                        <span class="text-blue-500 group-hover:text-blue-700 transition">Baca selengkapnya</span>
                        <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center text-blue-500 group-hover:bg-blue-500 group-hover:text-white transition-all transform group-hover:translate-x-2">
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </div>
                    </div>
                </a>
                @empty
                <div class="col-span-1 md:col-span-2 text-center py-20 bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                    <i class="fa-solid fa-file-circle-question text-5xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-bold text-gray-600">Artikel Tidak Ditemukan</h3>
                    <p class="text-gray-400 mt-2">Coba gunakan kata kunci pencarian yang lain.</p>
                </div>
                @endforelse
            </div>

            <div class="mt-12">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
</div>

<style>
    .page-enter { opacity: 0; transform: translateY(20px); animation: fadeInUp 0.8s ease-out forwards; }
    @keyframes fadeInUp { to { opacity: 1; transform: translateY(0); } }

    /* Tambahan CSS Scrollbar Elegan untuk Sidebar */
    .custom-scrollbar::-webkit-scrollbar {
        width: 5px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: transparent; 
        border-radius: 10px;
        transition: background-color 0.3s;
    }
    /* Scrollbar hanya terlihat saat area sidebar di-hover/disentuh mouse */
    .custom-scrollbar:hover::-webkit-scrollbar-thumb {
        background-color: #cbd5e1; 
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background-color: #94a3b8; 
    }
</style>
@endsection