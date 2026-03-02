@extends('layouts.app')

@section('content')
<div class="bg-white min-h-screen py-10 px-10">

    <nav class="text-sm text-gray-400 mb-8 max-w-7xl mx-auto">
        <a href="/" class="hover:text-blue-600">Beranda</a> / 
        <a href="{{ route('blog.index') }}" class="hover:text-blue-600">Blog</a> / 
        <span class="text-gray-800 font-medium">{{ $article['title'] }}</span>
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

        <article class="w-3/4">
            
            <span class="bg-blue-500 text-white text-[10px] font-bold px-3 py-1 rounded uppercase tracking-wide inline-block mb-3">
                {{ $article['cat'] }}
            </span>

            <h1 class="text-4xl font-bold text-gray-900 mb-4 leading-tight">
                {{ $article['title'] }}
            </h1>

            <div class="flex items-center gap-6 text-xs text-gray-500 mb-8 border-b border-gray-100 pb-8">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-full overflow-hidden bg-gray-200">
                         <img src="{{ asset('images/gambar_hero.jpg') }}" class="w-full h-full object-cover">
                    </div>
                    <span class="font-bold text-gray-700">{{ $article['author'] }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fa-regular fa-calendar"></i> {{ $article['date'] }}
                </div>
                <div class="flex items-center gap-2">
                    <i class="fa-regular fa-eye"></i> {{ $article['views'] }} dilihat
                </div>
                <div class="flex items-center gap-2">
                    <i class="fa-regular fa-comment"></i> 0 komentar
                </div>
            </div>

            <div class="rounded-2xl overflow-hidden mb-10 h-[450px]">
                <img src="{{ asset('images/' . $article['image']) }}" class="w-full h-full object-cover">
            </div>

            <div class="text-gray-600 leading-relaxed space-y-6 text-[15px]">
                
                <p>
                    {!! $article['content'] !!}
                </p>

                <blockquote class="border-l-4 border-blue-500 pl-6 py-2 my-8">
                    <p class="font-bold text-gray-800 text-lg italic">
                        "Data merupakan fondasi bagi masa depan perawatan kesehatan..."
                    </p>
                </blockquote>

                <div class="rounded-xl overflow-hidden my-8">
                    <img src="{{ asset('images/gambar_hero.jpg') }}" class="w-full h-auto object-cover">
                </div>

                <h3 class="text-2xl font-bold text-gray-900 mt-10 mb-4">Dibangun untuk Mobilitas</h3>
                <p>
                    (Ini teks statis tambahan untuk menjaga layout terlihat panjang dan bagus)...
                </p>
            </div>

            <div class="flex justify-between items-center border-t border-gray-100 pt-8 mt-12">
                <div class="flex items-center gap-2 text-sm text-gray-500">
                    <i class="fa-solid fa-tags text-gray-400"></i>
                    
                    @if(isset($article['tags']))
                        @foreach($article['tags'] as $tag)
                            <span class="hover:text-blue-600 cursor-pointer">{{ $tag }}</span>{{ !$loop->last ? ',' : '' }}
                        @endforeach
                    @endif
                </div>

                <div class="flex items-center gap-4">
                    <span class="text-sm font-bold text-gray-700">Bagikan postingan ini</span>
                    <button class="w-8 h-8 rounded-full bg-blue-500 text-white flex items-center justify-center hover:bg-blue-600 transition shadow-md shadow-blue-500/30">
                        <i class="fa-solid fa-share-nodes text-xs"></i>
                    </button>
                </div>
            </div>

        </article>

    </div>
</div>
@endsection