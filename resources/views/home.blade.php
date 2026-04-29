@extends('layouts.app')

@section('content')
<div class="px-4 md:px-10 py-6">
    
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 h-auto md:h-[500px]">
        
        <div class="col-span-1 lg:col-span-8 relative rounded-2xl overflow-hidden bg-blue-600 h-[300px] md:h-full group" 
             x-data="{ activeSlide: 0, slides: {{ $banners->count() }}, 
                       next() { this.activeSlide = this.activeSlide === this.slides - 1 ? 0 : this.activeSlide + 1 },
                       prev() { this.activeSlide = this.activeSlide === 0 ? this.slides - 1 : this.activeSlide - 1 },
                       init() { setInterval(() => this.next(), 5000) } }">
            
            @if($banners->count() > 0)
                @foreach($banners as $index => $banner)
                <div x-show="activeSlide === {{ $index }}" 
                     x-transition:enter="transition ease-out duration-700"
                     x-transition:enter-start="opacity-0 scale-105"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-700"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-105"
                     class="absolute inset-0">
                    <img src="{{ asset('images/'.$banner->image_path) }}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-r from-black/60 to-transparent"></div>
                    <div class="relative z-10 h-full flex flex-col justify-center px-8 md:px-16 text-white w-full md:w-2/3">
                        <h2 class="text-sm md:text-xl font-bold text-yellow-400 drop-shadow-md">Promo Spesial</h2>
                        <h1 class="text-3xl md:text-5xl font-black mt-2 leading-tight drop-shadow-lg">Belanja Kebutuhan Medis Sekarang</h1>
                        @if($banner->link_url)
                            <a href="{{ $banner->link_url }}" class="mt-6 bg-blue-600 text-white font-bold px-8 py-3 rounded-full w-max hover:bg-blue-700 transition shadow-lg shadow-blue-500/30">Beli Sekarang <i class="fa-solid fa-arrow-right ml-2"></i></a>
                        @else
                            <a href="{{ route('produk.index') }}" class="mt-6 bg-blue-600 text-white font-bold px-8 py-3 rounded-full w-max hover:bg-blue-700 transition shadow-lg shadow-blue-500/30">Belanja Sekarang <i class="fa-solid fa-arrow-right ml-2"></i></a>
                        @endif
                    </div>
                </div>
                @endforeach
                
                <button @click="prev()" class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/30 hover:bg-white text-gray-800 p-3 rounded-full backdrop-blur-sm transition opacity-0 group-hover:opacity-100 z-20"><i class="fa-solid fa-chevron-left"></i></button>
                <button @click="next()" class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/30 hover:bg-white text-gray-800 p-3 rounded-full backdrop-blur-sm transition opacity-0 group-hover:opacity-100 z-20"><i class="fa-solid fa-chevron-right"></i></button>
            @else
                <div class="absolute inset-0 bg-blue-500 flex flex-col items-center justify-center text-white">
                    <h1 class="text-4xl font-black">ALKES MAMED</h1>
                    <p>Pusat Alat Kesehatan Terlengkap</p>
                </div>
            @endif
        </div>

        <div class="col-span-1 lg:col-span-4 flex flex-col md:flex-row lg:flex-col gap-4 h-auto md:h-[200px] lg:h-full">
            @foreach($promoProducts as $index => $promo)
            <div class="flex-1 lg:h-1/2 rounded-2xl p-6 relative flex flex-col justify-center overflow-hidden min-h-[180px] group {{ $index == 0 ? 'bg-orange-50' : 'bg-emerald-50' }}">
                <div class="absolute right-0 bottom-0 w-1/2 h-full flex items-center justify-center opacity-60 group-hover:scale-110 transition duration-500">
                    <img src="{{ asset('images/'.($promo->mainImage->image_path ?? 'default.jpg')) }}" class="w-full h-full object-contain p-4">
                </div>
                <div class="relative z-10 w-2/3">
                    <span class="text-[10px] md:text-xs font-bold text-gray-400 uppercase">{{ $promo->category->name ?? 'Produk Unggulan' }}</span>
                    <h3 class="text-lg md:text-xl font-bold text-gray-800 leading-tight mt-1 line-clamp-2">{{ $promo->name }}</h3>
                    <p class="text-blue-600 font-black text-lg md:text-xl mt-1">Rp{{ number_format($promo->variants->first()->price ?? 0, 0, ',', '.') }}</p>
                    <a href="{{ route('produk.show', $promo->id) }}" class="mt-3 {{ $index == 0 ? 'bg-orange-500 hover:bg-orange-600' : 'bg-emerald-500 hover:bg-emerald-600' }} text-white font-bold px-5 py-2 rounded-lg text-xs md:text-sm w-max transition inline-block">Lihat Detail</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8">
        <div class="bg-white border border-gray-100 p-4 md:p-6 rounded-2xl flex flex-col sm:flex-row items-center gap-4 text-center sm:text-left shadow-sm hover:shadow-md transition cursor-default">
            <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-xl flex-shrink-0"><i class="fa-solid fa-shield-halved"></i></div>
            <div><h4 class="font-bold text-gray-800 text-sm">100% Original</h4><p class="text-[10px] text-gray-500 mt-0.5">Garansi Keaslian Medis</p></div>
        </div>
        <div class="bg-white border border-gray-100 p-4 md:p-6 rounded-2xl flex flex-col sm:flex-row items-center gap-4 text-center sm:text-left shadow-sm hover:shadow-md transition cursor-default">
            <div class="w-12 h-12 rounded-full bg-green-50 text-green-600 flex items-center justify-center text-xl flex-shrink-0"><i class="fa-solid fa-kit-medical"></i></div>
            <div><h4 class="font-bold text-gray-800 text-sm">Standar Kemenkes</h4><p class="text-[10px] text-gray-500 mt-0.5">Aman Digunakan</p></div>
        </div>
        <div class="bg-white border border-gray-100 p-4 md:p-6 rounded-2xl flex flex-col sm:flex-row items-center gap-4 text-center sm:text-left shadow-sm hover:shadow-md transition cursor-default">
            <div class="w-12 h-12 rounded-full bg-orange-50 text-orange-600 flex items-center justify-center text-xl flex-shrink-0"><i class="fa-solid fa-truck-fast"></i></div>
            <div><h4 class="font-bold text-gray-800 text-sm">Pengiriman Cepat</h4><p class="text-[10px] text-gray-500 mt-0.5">Aman & Terlindungi</p></div>
        </div>
        <div class="bg-white border border-gray-100 p-4 md:p-6 rounded-2xl flex flex-col sm:flex-row items-center gap-4 text-center sm:text-left shadow-sm hover:shadow-md transition cursor-default">
            <div class="w-12 h-12 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center text-xl flex-shrink-0"><i class="fa-regular fa-comments"></i></div>
            <div><h4 class="font-bold text-gray-800 text-sm">Konsultasi Gratis</h4><p class="text-[10px] text-gray-500 mt-0.5">Layanan CS 24/7</p></div>
        </div>
    </div>

    @if($flashSales->count() > 0)
    <div class="mt-12 md:mt-16 border-2 md:border-4 border-red-100 rounded-2xl md:rounded-3xl p-4 md:p-8 relative pt-12 md:pt-10" x-data="{ timer: '23:59:59' }">
        
        <div class="absolute -top-4 md:-top-6 left-4 md:left-10 bg-white px-2 md:px-4 flex items-center gap-3 md:gap-4">
            <h2 class="text-lg md:text-2xl font-black text-red-600 italic"><i class="fa-solid fa-bolt mr-1"></i> FLASH SALE</h2>
            <div class="bg-gray-800 text-white px-3 md:px-4 py-1.5 rounded-lg text-[10px] md:text-sm font-bold flex items-center gap-2">
                <i class="fa-regular fa-clock text-red-400"></i> Berakhir: <span x-text="timer"></span>
            </div>
        </div>

        <!-- SLIDER FLASH SALE (DESKTOP: 4 ITEM, MOBILE: 2 ITEM) -->
        @php
            $flashSaleItems = $flashSales;
            $clonedItems = $flashSales->count() > 2 ? $flashSales->take(4) : collect();
            $allSliderItems = $flashSaleItems->concat($clonedItems);
        @endphp

        <div class="relative w-full mt-4 md:mt-6 group"
             x-data="{
                 currentIndex: 0,
                 itemWidth: 0,
                 gap: 16,
                 totalOriginalItems: {{ $flashSales->count() }},
                 visibleItems: window.innerWidth >= 1024 ? 4 : 2,
                 isTransitioning: true,
                 
                 // Variabel untuk deteksi Swipe di Layar HP
                 startX: 0,
                 endX: 0,

                 init() {
                     this.updateConfig();
                     window.addEventListener('resize', () => this.updateConfig());
                 },
                 updateConfig() {
                     this.visibleItems = window.innerWidth >= 1024 ? 4 : 2;
                     let containerWidth = this.$refs.sliderContainer.clientWidth;
                     this.itemWidth = (containerWidth - (this.gap * (this.visibleItems - 1))) / this.visibleItems;
                 },
                 next() {
                     if(this.currentIndex >= this.totalOriginalItems) return; 

                     this.isTransitioning = true;
                     this.currentIndex++;

                     if (this.currentIndex === this.totalOriginalItems) {
                         setTimeout(() => {
                             this.isTransitioning = false;
                             this.currentIndex = 0;
                         }, 500); 
                     }
                 },
                 prev() {
                     if (this.currentIndex === 0) {
                         this.isTransitioning = false;
                         this.currentIndex = this.totalOriginalItems;
                         
                         setTimeout(() => {
                             this.isTransitioning = true;
                             this.currentIndex--;
                         }, 50);
                     } else {
                         this.isTransitioning = true;
                         this.currentIndex--;
                     }
                 },
                 // Fungsi eksekusi usapan jari
                 handleSwipe() {
                     if (!this.startX || !this.endX) return;
                     let diff = this.startX - this.endX;
                     
                     if (diff > 50) {
                         this.next(); // Usap ke kiri -> slide selanjutnya
                     } else if (diff < -50) {
                         this.prev(); // Usap ke kanan -> slide sebelumnya
                     }
                     
                     // Reset nilai sentuhan
                     this.startX = 0;
                     this.endX = 0;
                 }
             }">

            <!-- Container yg membungkus area geser dgn Sensor Sentuh -->
            <div x-ref="sliderContainer" 
                 class="overflow-hidden w-full relative py-2"
                 @touchstart="startX = $event.touches[0].clientX"
                 @touchmove="endX = $event.touches[0].clientX"
                 @touchend="handleSwipe()">
                
                <div x-ref="track" 
                     class="flex"
                     :class="isTransitioning ? 'transition-transform duration-500 ease-in-out' : ''"
                     :style="`transform: translateX(-${currentIndex * (itemWidth + gap)}px); gap: ${gap}px;`">

                    <!-- Loop Semua Produk (Asli + Kloningan) -->
                    @foreach($allSliderItems as $fs)
                    @php 
                        $variant = $fs->variants->first(); 
                        $price = $variant->price ?? 0;
                        $oldPrice = $variant->old_price ?? 0;
                        $discount = $oldPrice > 0 ? round((($oldPrice - $price) / $oldPrice) * 100) : 0;
                        $stock = $variant->stock ?? 0;
                    @endphp
                    
                    <div class="flex-shrink-0" :style="`width: ${itemWidth}px`">
                        <a href="{{ route('produk.show', $fs->id) }}" class="group bg-white p-4 md:p-5 rounded-2xl hover-card cursor-pointer relative border border-gray-100 shadow-sm transition block h-full w-full">
                            <div class="absolute top-3 left-3 z-10 flex gap-1">
                                @if($discount > 0)
                                    <span class="bg-red-500 text-white text-[10px] px-2 py-1 rounded font-black">{{ $discount }}% OFF</span>
                                @endif
                            </div>
                            
                            <div class="h-32 md:h-40 w-full flex items-center justify-center rounded-lg mb-4 overflow-hidden relative bg-gray-50 p-2">
                                <img src="{{ asset('images/'.($fs->mainImage->image_path ?? 'default.jpg')) }}" class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-500">
                            </div>

                            <p class="text-[9px] md:text-[10px] text-gray-400 font-bold uppercase truncate">{{ $fs->category->name ?? 'Promo' }}</p>
                            <h4 class="font-bold text-gray-800 text-xs md:text-sm mt-1 line-clamp-2 h-10">{{ $fs->name }}</h4>
                            
                            <div class="mt-2">
                                <span class="text-red-600 font-black text-sm md:text-lg">Rp{{ number_format($price, 0, ',', '.') }}</span>
                                @if($oldPrice > 0)
                                    <div class="text-gray-400 line-through text-[10px] md:text-xs">Rp{{ number_format($oldPrice, 0, ',', '.') }}</div>
                                @endif
                            </div>

                            <div class="mt-4">
                                <div class="w-full bg-red-100 h-1.5 rounded-full overflow-hidden">
                                    <div class="bg-red-500 h-full" style="width: {{ $stock > 10 ? '40' : '85' }}%"></div>
                                </div>
                                <div class="text-[10px] font-bold text-gray-500 mt-2">Sisa Stok: <span class="text-red-500">{{ $stock }} Pcs</span></div>
                            </div>
                        </a>
                    </div>
                    @endforeach

                </div>
            </div>

            <!-- Tombol Navigasi Kiri -->
            <button x-show="totalOriginalItems > visibleItems" @click="prev()" class="absolute -left-3 md:-left-5 top-1/2 -translate-y-1/2 bg-white text-gray-800 w-8 h-8 md:w-10 md:h-10 rounded-full shadow-lg shadow-gray-200/50 border border-gray-100 z-10 transition hover:bg-gray-50 flex items-center justify-center opacity-100 md:opacity-0 md:group-hover:opacity-100 pointer-events-auto md:pointer-events-none md:group-hover:pointer-events-auto">
                <i class="fa-solid fa-chevron-left text-xs md:text-sm"></i>
            </button>
            
            <!-- Tombol Navigasi Kanan -->
            <button x-show="totalOriginalItems > visibleItems" @click="next()" class="absolute -right-3 md:-right-5 top-1/2 -translate-y-1/2 bg-white text-gray-800 w-8 h-8 md:w-10 md:h-10 rounded-full shadow-lg shadow-gray-200/50 border border-gray-100 z-10 transition hover:bg-gray-50 flex items-center justify-center opacity-100 md:opacity-0 md:group-hover:opacity-100 pointer-events-auto md:pointer-events-none md:group-hover:pointer-events-auto">
                <i class="fa-solid fa-chevron-right text-xs md:text-sm"></i>
            </button>

        </div>
    </div>
    @endif

    @if($topCategories->count() >= 3)
    <div class="mt-16 mb-8 flex justify-between items-end">
        <h2 class="text-2xl font-black text-gray-800 border-b-4 border-blue-500 pb-2">Jelajahi Kategori</h2>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
        @foreach($topCategories as $index => $cat)
        @php 
            $colors = ['bg-blue-50', 'bg-orange-50', 'bg-emerald-50'];
            $btnColors = ['bg-blue-500 hover:bg-blue-600', 'bg-orange-500 hover:bg-orange-600', 'bg-emerald-500 hover:bg-emerald-600'];
            $color = $colors[$index % 3];
            $btnColor = $btnColors[$index % 3];
        @endphp
        <div class="{{ $color }} rounded-2xl p-6 md:p-8 relative h-40 md:h-48 overflow-hidden group shadow-sm border border-gray-100">
            <div class="relative z-10 w-2/3 h-full flex flex-col justify-center">
                <h3 class="text-lg md:text-2xl font-black text-gray-800 leading-tight">{{ $cat->name }}</h3>
                <p class="text-xs text-gray-500 mt-1 font-medium">{{ $cat->products_count }} Produk Tersedia</p>
                <a href="{{ route('produk.index', ['kategori' => $cat->slug]) }}" class="mt-4 {{ $btnColor }} text-white px-5 py-2 rounded-lg text-xs font-bold transition w-max shadow-md">Lihat Semua</a>
            </div>
            <div class="absolute right-0 bottom-0 w-1/2 h-full flex items-center justify-center opacity-40 group-hover:opacity-100 group-hover:scale-110 transition duration-500 p-2 md:p-4">
                {{-- Jika ada gambar kategori, tampilkan gambarnya. Jika kosong, pakai ikon box sebagai cadangan --}}
                @if($cat->image) 
                    <img src="{{ asset('images/' . $cat->image) }}" class="w-full h-full object-contain drop-shadow-md">
                @else
                    <i class="fa-solid fa-box-open text-7xl md:text-9xl text-gray-400"></i>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @endif

    @if($aboutUs)
    <div class="mt-20 py-12 border-t border-gray-100">
        <div class="text-center mb-10 md:mb-16">
            <h2 class="text-3xl font-black text-gray-800 inline-block border-b-4 border-blue-500 pb-2">{{ $aboutUs->title }}</h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-16 items-center max-w-6xl mx-auto">
            <div class="space-y-8">
                @if($aboutUs->excerpt)
                    <div class="bg-blue-50 p-6 md:p-8 rounded-2xl border-l-4 border-blue-500 shadow-sm">
                        <p class="text-gray-600 text-sm md:text-base leading-relaxed italic">"{{ $aboutUs->excerpt }}"</p>
                    </div>
                @endif
                @if($aboutUs->image_1)
                    <div class="rounded-3xl overflow-hidden shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
                        <img src="{{ asset('images/'.$aboutUs->image_1) }}" class="w-full h-64 md:h-80 object-cover">
                    </div>
                @endif
            </div>

            <div class="space-y-8 mt-8 md:mt-0">
                @if($aboutUs->image_2)
                    <div class="rounded-3xl overflow-hidden shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
                        <img src="{{ asset('images/'.$aboutUs->image_2) }}" class="w-full h-64 md:h-80 object-cover">
                    </div>
                @endif
                @if($aboutUs->content)
                    <div class="bg-white p-6 md:p-8 rounded-2xl border border-gray-100 shadow-sm">
                        <p class="text-gray-600 text-sm md:text-base leading-relaxed">{{ $aboutUs->content }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    @if($latestBlogs->count() > 0)
    <div class="mt-16 py-12 border-t border-gray-100 bg-slate-50 -mx-4 md:-mx-10 px-4 md:px-10">
        <div class="max-w-7xl mx-auto">
            <div class="flex justify-between items-end mb-8 md:mb-12">
                <div>
                    <h2 class="text-2xl font-black text-gray-800 border-b-4 border-blue-500 pb-2 inline-block">Artikel Kesehatan Terbaru</h2>
                    <p class="text-sm text-gray-500 mt-2 font-medium">Informasi, edukasi, dan fakta medis terkini.</p>
                </div>
                <a href="{{ route('blog.index') }}" class="hidden md:inline-block bg-white border border-gray-200 text-blue-600 font-bold px-6 py-2.5 rounded-full hover:bg-blue-50 transition shadow-sm">Lihat Semua Blog</a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <div class="lg:col-span-8 space-y-6">
                    @foreach($latestBlogs as $blog)
                    <a href="{{ route('blog.show', $blog->id) }}" class="flex flex-col sm:flex-row gap-5 bg-white p-4 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition group">
                        <div class="w-full sm:w-48 h-48 sm:h-32 rounded-xl overflow-hidden flex-shrink-0 relative">
                            <img src="{{ asset('images/'.($blog->image_path ?? 'default.jpg')) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            <div class="absolute top-2 left-2 bg-blue-600 text-white text-[10px] font-bold px-2 py-1 rounded">{{ $blog->category->name ?? 'Umum' }}</div>
                        </div>
                        <div class="flex flex-col justify-center">
                            <p class="text-[10px] font-bold text-gray-400 mb-1"><i class="fa-regular fa-calendar mr-1"></i> {{ $blog->created_at->format('d M Y') }} &nbsp; <i class="fa-solid fa-eye ml-2 mr-1"></i> {{ $blog->views }}x Dibaca</p>
                            <h3 class="text-lg font-bold text-gray-800 leading-tight group-hover:text-blue-600 transition">{{ $blog->title }}</h3>
                            <p class="text-xs text-gray-500 mt-2 line-clamp-2 leading-relaxed">{{ strip_tags($blog->content) }}</p>
                        </div>
                    </a>
                    @endforeach
                </div>

                <div class="lg:col-span-4">
                    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm sticky top-24">
                        <h3 class="text-lg font-black text-gray-800 mb-6">Topik Populer</h3>
                        <div class="flex flex-col gap-3">
                            @foreach($topBlogCategories as $bc)
                            <a href="#" class="flex justify-between items-center p-3 rounded-xl hover:bg-blue-50 border border-transparent hover:border-blue-100 transition group">
                                <span class="font-bold text-gray-700 group-hover:text-blue-600 text-sm"><i class="fa-solid fa-hashtag text-gray-300 group-hover:text-blue-400 mr-2"></i> {{ $bc->name }}</span>
                                <span class="bg-gray-100 group-hover:bg-blue-600 group-hover:text-white text-gray-500 text-[10px] font-black px-2.5 py-1 rounded-full transition">{{ $bc->posts_count }}</span>
                            </a>
                            @endforeach
                        </div>
                        
                        <div class="mt-8 p-5 bg-gradient-to-br from-blue-600 to-blue-800 rounded-xl text-white text-center">
                            <i class="fa-solid fa-envelope-open-text text-3xl mb-3 text-blue-200"></i>
                            <h4 class="font-bold text-sm mb-2">Langganan Newsletter</h4>
                            <p class="text-[10px] text-blue-100 mb-4">Dapatkan info kesehatan dan promo eksklusif.</p>
                            <div class="flex bg-white p-1 rounded-lg">
                                <input type="email" placeholder="Email Anda..." class="w-full bg-transparent border-0 text-xs text-gray-800 px-2 focus:ring-0">
                                <button class="bg-yellow-400 text-gray-900 px-3 py-1.5 rounded-md text-xs font-bold hover:bg-yellow-500">Kirim</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <a href="{{ route('blog.index') }}" class="md:hidden block text-center bg-white border border-gray-200 text-blue-600 font-bold px-6 py-3 rounded-xl mt-6 shadow-sm">Lihat Semua Blog</a>
        </div>
    </div>
    @endif

</div>
@endsection