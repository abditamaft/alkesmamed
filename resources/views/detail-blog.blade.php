@extends('layouts.app')

@section('content')
<div class="bg-white min-h-screen py-10 px-4 md:px-10" x-data="{ openFilter: false }">

    <nav class="text-sm text-gray-400 mb-8 max-w-7xl mx-auto page-enter">
        <a href="/" class="hover:text-blue-600">Beranda</a> / 
        <a href="{{ route('blog.index') }}" class="hover:text-blue-600">Blog</a> / 
        <span class="text-gray-800 font-medium">{{ Str::limit($article->title, 40) }}</span>
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
                        query: '', 
                        results: [], 
                        isTyping: false,
                        searchData() {
                            if(this.query.length > 0) {
                                this.isTyping = true;
                                fetch('/api/blog/search?q=' + this.query)
                                    .then(res => res.json())
                                    .then(data => {
                                        this.results = data;
                                        this.isTyping = false;
                                    })
                                    .catch(err => { this.isTyping = false; });
                            } else {
                                this.results = [];
                                this.isTyping = false;
                            }
                        } 
                     }">
                    <h3 class="text-lg font-bold mb-4 text-gray-800">Cari Artikel</h3>
                    <form action="{{ route('blog.index') }}" method="GET">
                        <div class="relative flex items-center">
                            <input type="text" name="cari" x-model="query" @input.debounce.250ms="searchData" placeholder="Ketik judul..." autocomplete="off"
                                   class="w-full bg-white border border-gray-200 rounded-full px-5 h-12 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 shadow-sm transition">
                            <div x-show="isTyping" class="absolute right-12 top-1/2 -translate-y-1/2 flex items-center">
                                <i class="fa-solid fa-spinner fa-spin text-blue-500 text-sm"></i>
                            </div>
                            <button type="submit" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-blue-600 flex items-center">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </button>
                        </div>
                    </form>
                    <div x-show="results.length > 0" @click.away="results = []" style="display: none;" class="absolute z-[99] w-full left-0 mt-2 bg-white rounded-xl shadow-2xl border border-gray-100 overflow-hidden max-h-64 overflow-y-auto custom-scrollbar">
                        <template x-for="item in results" :key="item.id">
                            <a :href="'/blog/' + item.id" class="flex items-center gap-3 p-3 hover:bg-blue-50 border-b border-gray-50 transition group">
                                <div class="w-10 h-10 rounded overflow-hidden flex-shrink-0">
                                    <img :src="item.image_path ? '/images/' + item.image_path : '/images/default.jpg'" class="w-full h-full object-cover">
                                </div>
                                <span class="text-xs font-bold text-gray-700 group-hover:text-blue-600 line-clamp-2" x-text="item.title"></span>
                            </a>
                        </template>
                    </div>
                </div>

                <div class="bg-[#f8f8f8] p-6 rounded-xl border border-gray-100">
                    <h3 class="text-lg font-bold mb-4 text-gray-800 flex justify-between items-center">Kategori</h3>
                    <ul class="space-y-3 text-sm font-medium">
                        @foreach($categories as $cat)
                        <li>
                            <a href="{{ route('blog.index', ['kategori' => $cat->slug]) }}" class="flex justify-between items-center group transition text-gray-500 hover:text-blue-600">
                                <span class="flex items-center gap-2">
                                    <i class="fa-solid fa-chevron-right text-[10px] opacity-0 group-hover:opacity-100 transition-opacity"></i>
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
                            $ringColor = 'ring-gray-100'; $badgeColor = 'bg-gray-500'; 
                            if($index == 0) { $ringColor = 'ring-yellow-400 ring-offset-2'; $badgeColor = 'bg-gradient-to-r from-yellow-400 to-yellow-600'; } 
                            elseif($index == 1) { $ringColor = 'ring-gray-300 ring-offset-2'; $badgeColor = 'bg-gradient-to-r from-gray-300 to-gray-500'; } 
                            elseif($index == 2) { $ringColor = 'ring-amber-700 ring-offset-2'; $badgeColor = 'bg-gradient-to-r from-amber-600 to-amber-800'; } 
                        @endphp
                        <a href="{{ route('blog.show', $pop->id) }}" class="flex gap-4 group cursor-pointer relative items-center">
                            <div class="w-16 h-16 rounded-full overflow-hidden flex-shrink-0 {{ $ringColor }} transition-all duration-300 group-hover:scale-105">
                                <img src="{{ $pop->image_path ? asset('images/' . $pop->image_path) : asset('images/default.jpg') }}" class="w-full h-full object-cover">
                            </div>
                            <div class="absolute -left-2 -top-1 w-6 h-6 rounded-full {{ $badgeColor }} text-white flex items-center justify-center text-[10px] font-black border-2 border-white shadow-md z-10">
                                #{{ $index + 1 }}
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-gray-800 group-hover:text-blue-600 leading-snug line-clamp-2 transition">{{ $pop->title }}</h4>
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

        <article class="w-full lg:w-3/4 page-enter">
            
            <div class="lg:hidden mb-6">
                <button @click="openFilter = true" class="w-full bg-blue-50 text-blue-600 border border-blue-200 font-bold py-3.5 rounded-xl flex justify-center items-center gap-2 hover:bg-blue-100 transition shadow-sm">
                    <i class="fa-solid fa-magnifying-glass"></i> Cari & Kategori
                </button>
            </div>

            <span class="bg-blue-500 text-white text-[10px] font-bold px-3 py-1 rounded uppercase tracking-wide inline-block mb-3 shadow-sm">
                {{ $article->category->name ?? 'Uncategorized' }}
            </span>

            <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-6 leading-tight">
                {{ $article->title }}
            </h1>

            <div class="flex flex-wrap items-center gap-4 md:gap-6 text-xs text-gray-500 mb-8 border-b border-gray-100 pb-8">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full overflow-hidden bg-blue-100 flex items-center justify-center text-blue-500 font-bold">
                        {{ strtoupper(substr($article->author->name ?? 'A', 0, 1)) }}
                    </div>
                    <div>
                        <span class="font-bold text-gray-700 block">{{ $article->author->name ?? 'Admin Mamed' }}</span>
                        <span class="text-[10px]">{{ $article->author->email ?? 'admin@alkesmamed.com' }}</span>
                    </div>
                </div>
                <div class="flex items-center gap-2 bg-gray-50 px-3 py-1.5 rounded-full">
                    <i class="fa-regular fa-calendar text-blue-500"></i> {{ $article->updated_at->format('d F Y') }}
                </div>
                <div class="flex items-center gap-2 bg-gray-50 px-3 py-1.5 rounded-full">
                    <i class="fa-solid fa-fire text-orange-400"></i> {{ number_format($article->views, 0, ',', '.') }} Dilihat
                </div>
            </div>

            <div class="rounded-2xl overflow-hidden mb-10 h-64 md:h-[450px] shadow-lg border border-gray-100">
                <img src="{{ $article->image_path ? asset('images/' . $article->image_path) : asset('images/default.jpg') }}" class="w-full h-full object-cover">
            </div>

            <div class="text-gray-700 leading-relaxed text-[15px] md:text-base prose prose-blue max-w-none prose-img:rounded-xl prose-img:shadow-md prose-headings:font-bold prose-headings:text-gray-900 prose-a:text-blue-600">
                {!! $article->content !!}
            </div>

            <div class="flex justify-between items-center border-t border-gray-100 pt-8 mt-12 mb-16">
                <div></div> <div class="flex items-center gap-4" 
                     x-data="{ 
                        shareContent() {
                            if (navigator.share) {
                                navigator.share({
                                    title: '{{ $article->title }}',
                                    text: 'Baca artikel menarik ini di Alkes Mamed!',
                                    url: window.location.href,
                                })
                            } else {
                                alert('Browser Anda tidak mendukung fitur share otomatis.');
                            }
                        }
                     }">
                    <span class="text-sm font-bold text-gray-700">Bagikan postingan ini</span>
                    <button @click="shareContent()" class="w-10 h-10 rounded-full bg-blue-500 text-white flex items-center justify-center hover:bg-blue-600 transition shadow-lg shadow-blue-500/30 transform hover:-translate-y-1">
                        <i class="fa-solid fa-share-nodes"></i>
                    </button>
                </div>
            </div>

            @if($related_posts->count() > 0)
            <div class="border-t-2 border-dashed border-gray-200 pt-12" x-data="{
                slider: null,
                isAnimating: false,
                init() { 
                    this.slider = this.$refs.slider; 
                },
                next() {
                    if (this.isAnimating) return;
                    this.isAnimating = true;
                    
                    // Geser halus ke kanan
                    this.slider.scrollBy({ left: 320, behavior: 'smooth' });
                    
                    // Sihir Ilusi: Pindahkan elemen pertama ke paling belakang secara diam-diam
                    setTimeout(() => {
                        this.slider.style.scrollBehavior = 'auto'; // Matikan animasi
                        this.slider.appendChild(this.slider.firstElementChild); // Lempar ke belakang
                        this.slider.scrollLeft -= 320; // Tahan posisi kamera agar tidak lompat
                        
                        // Nyalakan animasi lagi
                        setTimeout(() => {
                            this.slider.style.scrollBehavior = 'smooth';
                            this.isAnimating = false;
                        }, 50);
                    }, 400); // Eksekusi setelah animasi scroll selesai
                },
                prev() {
                    if (this.isAnimating) return;
                    this.isAnimating = true;
                    
                    // Sihir Ilusi: Ambil elemen terakhir, taruh di paling depan secara diam-diam
                    this.slider.style.scrollBehavior = 'auto';
                    this.slider.prepend(this.slider.lastElementChild);
                    this.slider.scrollLeft += 320; // Sesuaikan kamera
                    
                    // Baru lakukan geser halus ke kiri
                    setTimeout(() => {
                        this.slider.style.scrollBehavior = 'smooth';
                        this.slider.scrollBy({ left: -320, behavior: 'smooth' });
                        
                        setTimeout(() => { this.isAnimating = false; }, 400);
                    }, 50);
                }
            }">
                <div class="flex justify-between items-end mb-6">
                    <h3 class="text-2xl font-extrabold text-gray-900">Artikel Serupa</h3>
                    
                    <div class="flex gap-2">
                        <button @click="prev()" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-blue-500 hover:text-white transition flex items-center justify-center text-gray-600 shadow-sm">
                            <i class="fa-solid fa-chevron-left text-xs"></i>
                        </button>
                        <button @click="next()" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-blue-500 hover:text-white transition flex items-center justify-center text-gray-600 shadow-sm">
                            <i class="fa-solid fa-chevron-right text-xs"></i>
                        </button>
                    </div>
                </div>

                <div x-ref="slider" class="flex gap-6 overflow-x-auto pb-6 snap-x snap-mandatory custom-scrollbar" style="scroll-behavior: smooth;">
                    @foreach($related_posts as $related)
                    <a href="{{ route('blog.show', $related->id) }}" class="flex-shrink-0 w-64 md:w-72 bg-white border border-gray-100 rounded-2xl p-3 hover:shadow-xl transition-all group snap-start block">
                        <div class="h-40 w-full rounded-xl overflow-hidden mb-4 relative">
                            <img src="{{ $related->image_path ? asset('images/' . $related->image_path) : asset('images/default.jpg') }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                        </div>
                        <p class="text-[10px] text-gray-400 font-bold mb-2 flex items-center gap-2">
                            <i class="fa-regular fa-calendar"></i> {{ $related->updated_at->format('d M Y') }}
                        </p>
                        <h4 class="font-bold text-gray-800 text-sm group-hover:text-blue-600 transition line-clamp-2 leading-snug">
                            {{ $related->title }}
                        </h4>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

        </article>
    </div>
</div>

<style>
    .page-enter { opacity: 0; transform: translateY(20px); animation: fadeInUp 0.8s ease-out forwards; }
    @keyframes fadeInUp { to { opacity: 1; transform: translateY(0); } }

    .custom-scrollbar::-webkit-scrollbar { height: 5px; width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background-color: transparent; border-radius: 10px; transition: background-color 0.3s; }
    .custom-scrollbar:hover::-webkit-scrollbar-thumb { background-color: #cbd5e1; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background-color: #94a3b8; }
</style>
@endsection