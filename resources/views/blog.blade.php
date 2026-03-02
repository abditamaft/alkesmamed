@extends('layouts.app')

@section('content')
<div class="bg-white min-h-screen py-10 px-10">

    <nav class="text-sm text-gray-400 mb-8 max-w-7xl mx-auto">
        <a href="/" class="hover:text-blue-600">Beranda</a> / 
        <span class="text-gray-800 font-medium">Blog</span>
    </nav>

    <div class="max-w-7xl mx-auto flex gap-10">
        
        <aside class="w-1/4 space-y-8 h-fit sticky top-24">
            
            <div class="bg-[#f8f8f8] p-6 rounded-xl border border-gray-100">
                <h3 class="text-lg font-bold mb-4 text-gray-800">Cari</h3>
                <div class="relative">
                    <input type="text" placeholder="Cari..." class="w-full bg-white border-0 rounded-full px-4 py-3 text-sm focus:ring-1 focus:ring-blue-500 shadow-sm">
                    <button class="absolute right-3 top-3 text-gray-400 hover:text-blue-600">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>
            </div>

            <div class="bg-[#f8f8f8] p-6 rounded-xl border border-gray-100">
                <h3 class="text-lg font-bold mb-4 text-gray-800">Kategori</h3>
                <ul class="space-y-3 text-sm text-gray-500">
                    <li class="hover:text-blue-600 cursor-pointer transition">• Kehidupan Sehari-hari (8)</li>
                    <li class="hover:text-blue-600 cursor-pointer transition">• Kesehatan 5.0 (1)</li>
                    <li class="hover:text-blue-600 cursor-pointer transition">• Berita Hangat (4)</li>
                    <li class="hover:text-blue-600 cursor-pointer transition">• Tidak Dikategorikan (2)</li>
                    <li class="hover:text-blue-600 cursor-pointer transition">• Kesehatan Anda (3)</li>
                </ul>
            </div>

            <div class="bg-[#f8f8f8] p-6 rounded-xl border border-gray-100">
                <h3 class="text-lg font-bold mb-6 text-gray-800">Posting Populer</h3>
                
                <div class="space-y-6">
                    @foreach($popular_posts as $pop)
                    <a href="{{ route('blog.show', $pop['id']) }}" class="flex gap-4 group cursor-pointer">
                        <div class="w-16 h-16 rounded-lg overflow-hidden flex-shrink-0">
                            <img src="{{ asset('images/' . $pop['image']) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-gray-800 group-hover:text-blue-600 leading-snug line-clamp-2 transition">
                                {{ $pop['title'] }}
                            </h4>
                            <p class="text-[10px] text-gray-400 mt-1">{{ $pop['date'] }}</p>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>

            <div class="bg-[#f8f8f8] p-6 rounded-xl border border-gray-100">
                <h3 class="text-lg font-bold mb-4 text-gray-800">Tag Populer</h3>
                <div class="flex flex-wrap gap-2">
                    <span class="bg-white px-3 py-1 rounded-full text-xs text-gray-500 border border-gray-200 hover:bg-blue-600 hover:text-white hover:border-blue-600 cursor-pointer transition">alzheimer</span>
                    <span class="bg-white px-3 py-1 rounded-full text-xs text-gray-500 border border-gray-200 hover:bg-blue-600 hover:text-white hover:border-blue-600 cursor-pointer transition">analitik</span>
                    <span class="bg-white px-3 py-1 rounded-full text-xs text-gray-500 border border-gray-200 hover:bg-blue-600 hover:text-white hover:border-blue-600 cursor-pointer transition">covid19</span>
                    <span class="bg-white px-3 py-1 rounded-full text-xs text-gray-500 border border-gray-200 hover:bg-blue-600 hover:text-white hover:border-blue-600 cursor-pointer transition">kesehatan</span>
                </div>
            </div>

        </aside>

        <div class="w-3/4">
            <div class="grid grid-cols-2 gap-8">
                
                @foreach($articles as $index => $article)
                
                <a href="{{ route('blog.show', $article['id']) }}" 
                   x-data="{ shown: false }" 
                   x-intersect.margin.10%="shown = true"
                   :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-20'"
                   class="bg-white group cursor-pointer transition-all duration-700 ease-out"
                   style="transition-delay: {{ ($index % 2) * 150 }}ms">
                    
                    <div class="rounded-xl overflow-hidden relative h-64 mb-6">
                        <img src="{{ asset('images/' . $article['image']) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                        <span class="absolute top-4 left-4 bg-blue-500 text-white text-[10px] font-bold px-3 py-1 rounded uppercase tracking-wide">
                            {{ $article['cat'] }}
                        </span>
                    </div>

                    <div class="flex items-center gap-4 text-xs text-gray-400 mb-3">
                        <div class="flex items-center gap-1">
                            <i class="fa-regular fa-calendar"></i> {{ $article['date'] }}
                        </div>
                        <div class="flex items-center gap-1">
                            <i class="fa-regular fa-eye"></i> {{ $article['views'] }} dilihat
                        </div>
                    </div>

                    <h2 class="text-xl font-bold text-gray-800 mb-3 group-hover:text-blue-600 transition leading-snug">
                        {{ $article['title'] }}
                    </h2>

                    <div class="inline-flex items-center text-sm font-bold text-gray-800 hover:text-blue-600 transition mt-auto group/link">
                        Baca selengkapnya 
                        <i class="fa-solid fa-arrow-right ml-2 text-xs transform group-hover/link:translate-x-1 transition"></i>
                    </div>
                </a>
                @endforeach

            </div>

            <div class="mt-16 flex justify-center gap-2">
                <button class="w-10 h-10 rounded-full bg-blue-500 text-white font-bold shadow-lg shadow-blue-500/30">1</button>
                <button class="w-10 h-10 rounded-full bg-white text-gray-600 font-bold hover:bg-gray-100 transition border border-gray-100">2</button>
                <button class="w-10 h-10 rounded-full bg-white text-gray-600 font-bold hover:bg-gray-100 transition border border-gray-100">3</button>
                <button class="w-10 h-10 rounded-full bg-white text-gray-600 font-bold hover:bg-gray-100 transition border border-gray-100"><i class="fa-solid fa-angles-right text-xs"></i></button>
            </div>
        </div>

    </div>
</div>
@endsection