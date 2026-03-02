@extends('layouts.app')

@section('content')
<div class="px-4 md:px-10 py-6">
    
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 h-auto md:h-[500px]">
        
        <div class="col-span-1 lg:col-span-8 relative rounded-2xl overflow-hidden bg-blue-400 h-[300px] md:h-full">
            <div class="absolute inset-0">
                <img src="{{ asset('images/banner_utama.png') }}" class="w-full h-full object-cover">
            </div>

            <div class="absolute inset-0 bg-gradient-to-r from-cyan-400/80 to-blue-500/60"></div>
            
            <div class="relative z-10 h-full flex flex-col justify-center px-6 md:px-12 text-white">
                <h2 class="text-sm md:text-lg font-medium">Mulai Dari Rp350.000</h2>
                <h1 class="text-3xl md:text-5xl font-bold mt-2 leading-tight">Alat Kesehatan Terbaru</h1>
                <a href="/produk" class="mt-4 md:mt-6 bg-yellow-400 text-black font-bold px-6 md:px-8 py-2 md:py-3 rounded-full w-max hover:scale-105 transition inline-block text-sm md:text-base">Beli Sekarang</a>
            </div>
        </div>

        <div class="col-span-1 lg:col-span-4 flex flex-col md:flex-row lg:flex-col gap-4 h-auto md:h-[200px] lg:h-full">
            <div class="flex-1 lg:h-1/2 bg-blue-50 rounded-2xl p-6 relative flex flex-col justify-center overflow-hidden min-h-[180px]">
                <div class="absolute right-0 bottom-0 w-1/2 h-full flex items-center justify-center opacity-40">
                    <img src="{{ asset('images/termometer.webp') }}" class="w-full h-full object-contain">
                </div>
                <div class="relative z-10 w-2/3">
                    <span class="text-[10px] md:text-xs font-bold text-gray-400 uppercase">Perlengkapan Medis Rumah</span>
                    <h3 class="text-lg md:text-xl font-bold text-gray-800 leading-tight mt-1">Portable Mesh Atomizer</h3>
                    <p class="text-orange-500 font-bold text-lg md:text-xl mt-1">Rp600.000</p>
                    <a href="/produk" class="mt-3 bg-orange-500 text-white px-4 py-2 rounded-lg text-xs md:text-sm w-max hover:bg-orange-600 inline-block">Shop Now</a>
                </div>
            </div>
            
            <div class="flex-1 lg:h-1/2 bg-gray-100 rounded-2xl p-6 relative flex flex-col justify-center overflow-hidden min-h-[180px]">
                <div class="absolute right-0 bottom-0 w-1/2 h-full opacity-40 flex items-center justify-center">
                    <img src="{{ asset('images/vaporizer.jpg') }}" class="w-full h-full object-contain">
                </div>
                <div class="relative z-10 w-2/3">
                    <span class="text-[10px] md:text-xs font-bold text-gray-400 uppercase">Perlengkapan Medis Rumah</span>
                    <h3 class="text-lg md:text-xl font-bold text-gray-800 leading-tight mt-1">Sanitizer Gel Alcohol</h3>
                    <p class="text-orange-500 font-bold text-lg md:text-xl mt-1">Rp300.000</p>
                    <a href="/produk" class="mt-3 bg-blue-500 text-white px-4 py-2 rounded-lg text-xs md:text-sm w-max hover:bg-blue-600 inline-block">Shop Now</a>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-12 md:mt-16 border-2 md:border-4 border-blue-200 rounded-2xl md:rounded-3xl p-4 md:p-8 relative pt-12 md:pt-8" x-data="{ timer: 'Hitung Mundur!' }">
        
        <div class="absolute -top-4 md:-top-6 left-4 md:left-10 bg-white px-2 md:px-4 flex flex-col sm:flex-row items-start sm:items-center gap-2 md:gap-4 rounded-lg md:rounded-none">
            <h2 class="text-lg md:text-2xl font-bold text-gray-800">Penawaran Harian Ini</h2>
            <div class="bg-orange-500 text-white px-3 md:px-4 py-1 rounded-full text-[10px] md:text-sm font-bold" x-text="'Berakhir: ' + timer"></div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 md:gap-8 mt-2 md:mt-0">
            @foreach($products as $p)
            <a href="/produk" class="group bg-white p-3 md:p-4 rounded-xl hover-card cursor-pointer relative border border-gray-100 md:border-none shadow-sm md:shadow-none hover:shadow-md transition">
                <div class="absolute top-2 left-2 z-10">
                    <span class="bg-orange-500 text-white text-[8px] md:text-[10px] px-2 py-1 rounded font-bold uppercase">{{ $p['tag'] }}</span>
                </div>
                
                <div class="h-32 md:h-48 w-full bg-transparent flex items-center justify-center rounded-lg mb-3 overflow-hidden relative">
                    <img src="{{ asset('images/produk_mamed.jpg') }}" class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-500">
                    
                    <div class="absolute bottom-2 left-0 right-0 justify-center gap-2 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-y-4 group-hover:translate-y-0 hidden md:flex">
                        <button class="bg-gray-800 text-white p-2 lg:p-3 rounded-full hover:bg-blue-600"><i class="fa-solid fa-cart-shopping text-xs"></i></button>
                        <button class="bg-gray-800 text-white p-2 lg:p-3 rounded-full hover:bg-blue-600"><i class="fa-regular fa-heart text-xs"></i></button>
                    </div>
                </div>

                <p class="text-[8px] md:text-[10px] text-gray-400 font-bold uppercase truncate">{{ $p['cat'] }}</p>
                <h4 class="font-bold text-gray-700 text-xs md:text-sm mt-1 line-clamp-2">{{ $p['name'] }}</h4>
                <div class="mt-2 bg-yellow-300 inline-block px-2 py-0.5 rounded">
                    <span class="text-gray-900 font-bold text-[10px] md:text-xs">{{ $p['price'] }}</span>
                </div>
                <div class="text-gray-400 line-through text-[8px] md:text-[10px] mt-1">{{ $p['old'] }}</div>

                <div class="mt-3 md:mt-4">
                    <div class="w-full bg-gray-100 h-1 md:h-1.5 rounded-full overflow-hidden">
                        <div class="bg-blue-400 h-full" style="width: {{ ($p['sold']/$p['total'])*100 }}%"></div>
                    </div>
                    <div class="flex justify-between text-[8px] md:text-[10px] font-bold text-gray-400 mt-1.5 md:mt-2">
                        <span>Habis: {{ $p['sold'] }}</span>
                        <span>Sisa: {{ $p['total'] - $p['sold'] }}</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 mt-8 md:mt-12">
        <div class="bg-orange-50 rounded-2xl p-6 md:p-8 relative h-40 md:h-48 overflow-hidden group">
            <div class="relative z-10 w-2/3">
                <span class="text-[8px] md:text-[10px] font-bold text-gray-400 uppercase">Pribadi</span>
                <h3 class="text-lg md:text-xl font-bold text-gray-800 mt-1 leading-tight">Temperature Gun</h3>
                <p class="text-orange-500 font-bold text-base md:text-lg mt-1">Rp900.000</p>
                <a href="/produk" class="mt-3 bg-blue-500 text-white px-4 md:px-5 py-1.5 md:py-2 rounded-lg text-[10px] md:text-xs font-bold hover:bg-blue-600 transition inline-block">Shop Now</a>
            </div>
            <div class="absolute right-0 bottom-0 w-1/2 h-full flex items-center justify-center opacity-30">
                <img src="{{ asset('images/produk_mamed.jpg') }}" class="w-full h-full object-contain">
            </div>
        </div>

        <div class="bg-emerald-50 rounded-2xl p-6 md:p-8 relative h-40 md:h-48 overflow-hidden group">
            <div class="relative z-10 w-2/3">
                <span class="text-[8px] md:text-[10px] font-bold text-gray-400 uppercase">Peralatan Rumah</span>
                <h3 class="text-lg md:text-xl font-bold text-gray-800 mt-1 leading-tight">Steam Vaporizer</h3>
                <p class="text-orange-500 font-bold text-base md:text-lg mt-1">Rp1.450.000</p>
                <a href="/produk" class="mt-3 bg-blue-500 text-white px-4 md:px-5 py-1.5 md:py-2 rounded-lg text-[10px] md:text-xs font-bold hover:bg-blue-600 transition inline-block">Shop Now</a>
            </div>
            <div class="absolute right-0 bottom-0 w-1/2 h-full flex items-center justify-center opacity-30">
                <img src="{{ asset('images/produk_mamed.jpg') }}" class="w-full h-full object-contain">
            </div>
        </div>

        <div class="bg-blue-50 rounded-2xl p-6 md:p-8 relative h-40 md:h-48 overflow-hidden group">
            <div class="absolute top-2 right-2 md:top-4 md:right-4 bg-yellow-400 w-10 h-10 md:w-12 md:h-12 rounded-full flex flex-col items-center justify-center text-[8px] md:text-[10px] font-bold leading-none z-20">
                <span>19%</span>
                <span>Off</span>
            </div>
            <div class="relative z-10 w-2/3">
                <span class="text-[8px] md:text-[10px] font-bold text-gray-400 uppercase">Rumah Sakit</span>
                <h3 class="text-lg md:text-xl font-bold text-gray-800 mt-1 leading-tight">Stainless Scissors</h3>
                <p class="text-orange-500 font-bold text-base md:text-lg mt-1">Rp200.000</p>
                <a href="/produk" class="mt-3 bg-orange-500 text-white px-4 md:px-5 py-1.5 md:py-2 rounded-lg text-[10px] md:text-xs font-bold hover:bg-orange-600 transition inline-block">Shop Now</a>
            </div>
            <div class="absolute right-0 bottom-0 w-1/2 h-full flex items-center justify-center opacity-30">
                <img src="{{ asset('images/produk_mamed.jpg') }}" class="w-full h-full object-contain">
            </div>
        </div>
    </div>
</div>
@endsection