@php
    // Ambil data keranjang asli dari DB untuk Mini Cart di Header
    $miniCartCarts = auth()->check() ? auth()->user()->carts()->with('variant.product.mainImage')->get() : collect([]);
    
    // Format data untuk AlpineJS
    $miniCartData = $miniCartCarts->map(function($cart) {
        return [
            'id' => $cart->id,
            'name' => $cart->variant->product->name,
            'variant_name' => $cart->variant->variant_name,
            'price' => $cart->variant->price,
            'qty' => $cart->quantity,
            'img' => $cart->variant->product->mainImage ? asset('images/' . $cart->variant->product->mainImage->image_path) : asset('images/default.jpg')
        ];
    });
    $navCategories = \App\Models\Category::with(['products' => function($query) {
        $query->take(6); 
    }])->get();
    $blogCategories = \App\Models\BlogCategory::orderBy('name', 'asc')->get();
@endphp
<header x-data="{ 
    isSticky: false, 
    openMenu: null,
    closeTimeout: null,
    mobileMenuOpen: false 
}" 
@scroll.window="isSticky = (window.pageYOffset > 50)"
:class="isSticky ? 'fixed top-0 left-0 w-full z-50 bg-white shadow-lg sticky-header' : 'relative bg-white'"
class="transition-all duration-300 font-sans">
    
    <div class="hidden md:flex justify-between items-center px-10 py-2 border-b text-xs text-gray-600">
        <div class="flex gap-4">
            <span>082332116115 | Jl. Muwuh, Sumberagung, Plaosan, Magetan</span>
            <span>medicalmagetan@gmail.com</span>
        </div>
        
        <div class="flex items-center gap-4">
            @guest
                <a href="/login-register" class="hover:text-blue-500 transition font-medium">Log in / Sign Up</a>
            @endguest

            @auth
                <a href="{{ route('profile.index') }}" class="flex items-center gap-2 font-bold text-gray-800 hover:text-blue-600 transition">
                    <i class="fa-regular fa-user"></i>
                    <span>Hi, {{ strtok(Auth::user()->name, ' ') }}</span>
                </a>
            @endauth
        </div>
    </div>

    <nav class="flex justify-between items-center px-4 md:px-10 py-3 md:py-4 relative">
        
        <div class="flex items-center gap-3 md:gap-2">
            <button @click="mobileMenuOpen = true" class="md:hidden text-gray-700 hover:text-blue-600 focus:outline-none text-xl">
                <i class="fa-solid fa-bars"></i>
            </button>
            
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 md:w-10 md:h-10 bg-gray-800 rounded-full flex items-center justify-center text-white font-bold text-xs md:text-base">MG</div>
                <span class="text-lg md:text-2xl font-bold tracking-tighter text-gray-800 uppercase">Alkes Mamed</span>
            </div>
        </div>

        <ul class="hidden md:flex items-center gap-8 font-semibold text-gray-700 z-50">
            <li><a href="/" class="hover:text-blue-600 py-6 inline-block">Beranda</a></li>
            
            <li><a href="{{ route('produk.index') }}" class="hover:text-blue-600 py-6 inline-block">Produk</a></li>

            <li class="group h-full flex items-center" 
                @mouseenter="clearTimeout(closeTimeout); openMenu = 'kategori'" 
                @mouseleave="closeTimeout = setTimeout(() => openMenu = null, 250)">
                <button class="flex items-center gap-1 hover:text-blue-600 focus:outline-none py-6 h-full">
                    Kategori <i class="fa-solid fa-chevron-down text-[10px] transition-transform duration-300" :class="openMenu === 'kategori' ? 'rotate-180' : ''"></i>
                </button>
                
                <div x-show="openMenu === 'kategori'" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-cloak
                     class="absolute left-0 top-[100%] w-full bg-white shadow-xl border-t border-gray-100 z-50 cursor-default">
                     
                     <div class="max-w-7xl mx-auto px-4 md:px-10 py-8 md:py-10 h-[50vh] min-h-[400px] overflow-y-auto custom-scrollbar">
                         
                         <div class="flex flex-col lg:flex-row gap-10">

                             <div class="w-full lg:w-3/4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-10 items-start content-start">
                                 @foreach($navCategories as $cat)
                                 <div class="w-full">
                                     
                                     <a href="{{ route('produk.index', ['kategori' => $cat->slug]) }}" class="block font-bold text-gray-800 text-sm uppercase tracking-wide mb-3 border-b-2 border-gray-100 w-max pb-1 hover:text-blue-600 hover:border-blue-600 transition-all">
                                         {{ $cat->name }}
                                     </a>
                                     
                                     <ul class="space-y-2 text-xs md:text-sm text-gray-500 font-normal">
                                         @forelse($cat->products as $prod)
                                         <li>
                                             <a href="{{ route('produk.show', $prod->id) }}" class="hover:text-blue-600 transition block leading-snug">
                                                 {{ $prod->name }}
                                             </a>
                                         </li>
                                         @empty
                                         <li><span class="text-gray-300 italic text-[10px]">Belum ada produk</span></li>
                                         @endforelse
                                     </ul>
                                     
                                 </div>
                                 @endforeach
                             </div>

                             <div class="w-full lg:w-1/4 flex-shrink-0">
                                 <div class="sticky top-0 relative overflow-hidden rounded-xl bg-[#f4ece6] flex flex-col justify-center p-6 group/card min-h-[250px] w-full">
                                     <div class="z-10 w-2/3">
                                         <span class="text-[10px] font-bold uppercase text-gray-500 tracking-widest">Personal</span>
                                         <h3 class="text-xl font-bold text-gray-900 mt-1 mb-2 leading-tight">Temperature Gun</h3>
                                         <div class="flex items-center gap-2 mb-4">
                                             <span class="text-lg font-bold text-orange-500">$35.00</span>
                                             <span class="text-xs text-gray-400 line-through decoration-1">$45.00</span>
                                         </div>
                                         <a href="/produk" class="bg-blue-500 text-white text-[10px] font-bold px-4 py-2 rounded-full hover:bg-blue-600 transition shadow-lg inline-block">
                                             Shop now
                                         </a>
                                     </div>
                                     <div class="absolute top-4 right-4 bg-yellow-400 w-12 h-12 rounded-full flex flex-col items-center justify-center font-bold text-gray-900 z-20 shadow-sm">
                                         <span class="text-sm leading-none">22%</span>
                                         <span class="text-[8px] uppercase leading-none">off</span>
                                     </div>
                                     <div class="absolute right-[-20px] top-1/2 transform -translate-y-1/2 w-2/3 h-full flex items-center">
                                         <img src="{{ asset('images/gambar_hero.jpg') }}" class="w-full h-full object-contain object-center scale-110 group-hover/card:scale-125 transition duration-700">
                                     </div>
                                 </div>
                             </div>
                             
                         </div>
                     </div>
                </div>
            </li>

            <li class="relative group" 
                @mouseenter="clearTimeout(closeTimeout); openMenu = 'blog'" 
                @mouseleave="closeTimeout = setTimeout(() => openMenu = null, 250)">
                <button class="flex items-center gap-1 hover:text-blue-600 focus:outline-none py-6">
                    Blog <i class="fa-solid fa-chevron-down text-[10px] transition-transform duration-300" :class="openMenu === 'blog' ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="openMenu === 'blog'" 
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform -translate-y-4"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    x-cloak
                    class="absolute left-0 mt-0 w-56 bg-white border border-gray-100 shadow-xl rounded-md z-50">
                    <a href="/blog" class="block px-4 py-3 border-b hover:bg-blue-50 hover:text-blue-600 text-sm font-normal">Kesehatan Terbaru</a>
                    <a href="/blog" class="block px-4 py-3 hover:bg-blue-50 hover:text-blue-600 text-sm font-normal">Tips Medis</a>
                </div><div x-show="openMenu === 'blog'" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform -translate-y-4"
                     x-transition:enter-end="opacity-100 transform translate-y-0"
                     x-cloak
                     class="absolute left-0 mt-0 w-56 bg-white border border-gray-100 shadow-xl rounded-md z-50 max-h-60 overflow-y-auto custom-scrollbar">
                     
                     @forelse($blogCategories as $bCat)
                         <a href="{{ route('blog.index', ['kategori' => $bCat->slug]) }}" class="block px-4 py-3 border-b border-gray-50 hover:bg-blue-50 hover:text-blue-600 text-sm font-normal transition">
                             {{ $bCat->name }}
                         </a>
                     @empty
                         <span class="block px-4 py-3 text-sm text-gray-400 italic">Belum ada kategori</span>
                     @endforelse
                </div>
            </li>

            <li><a href="/kontak" class="hover:text-blue-600 py-6 inline-block">Kontak</a></li>
        </ul>

        <div class="flex items-center gap-3 md:gap-5 text-lg md:text-xl text-gray-700">
            <div class="relative flex items-center z-50" x-data="{ 
                searchOpen: false, 
                query: '', 
                results: [], 
                loading: false,
                search() {
                    if(this.query.length < 2) { this.results = []; return; }
                    this.loading = true;
                    // Fetch ke API Produk
                    fetch(`/api/produk/search?q=${this.query}`)
                        .then(res => res.json())
                        .then(data => { this.results = data; this.loading = false; })
                        .catch(() => { this.loading = false; });
                }
            }">
                <button @click="searchOpen = !searchOpen; if(searchOpen) $nextTick(() => $refs.searchInput.focus())" class="hidden sm:block text-gray-700 hover:text-blue-600 transition-colors outline-none relative z-10">
                    <i class="fa-solid fa-magnifying-glass text-xl md:text-2xl mt-1"></i>
                </button>

                <div x-show="searchOpen" @click.outside="searchOpen = false" 
                     x-transition:enter="transition ease-out duration-300" 
                     x-transition:enter-start="opacity-0 translate-x-8" 
                     x-transition:enter-end="opacity-100 translate-x-0" 
                     x-transition:leave="transition ease-in duration-200" 
                     x-transition:leave-start="opacity-100 translate-x-0" 
                     x-transition:leave-end="opacity-0 translate-x-8" 
                     class="absolute right-full mr-4 top-1/2 -translate-y-1/2 hidden sm:block w-[300px]" x-cloak>
                     
                    <div class="relative w-full">
                        <input x-ref="searchInput" type="text" x-model="query" @input.debounce.300ms="search" placeholder="Cari alat medis..." 
                               class="w-full bg-white border border-gray-200 shadow-lg rounded-full pl-5 pr-10 py-2.5 text-sm focus:outline-none focus:border-blue-500 focus:ring-1">
                        
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 flex items-center">
                            <i x-show="loading" class="fa-solid fa-spinner fa-spin text-blue-500 text-sm"></i>
                            <button x-show="!loading" @click="searchOpen = false; query = ''; results = []" class="text-gray-400 hover:text-red-500"><i class="fa-solid fa-xmark"></i></button>
                        </div>
                    </div>
                    
                    <div x-show="results.length > 0" class="absolute top-full right-0 mt-3 w-[350px] bg-white rounded-xl shadow-2xl border border-gray-100 overflow-hidden max-h-80 overflow-y-auto custom-scrollbar">
                        <template x-for="item in results" :key="item.id">
                            <a :href="`/detail-produk/${item.id}`" class="flex gap-3 p-3 hover:bg-blue-50 border-b border-gray-50 transition group">
                                <div class="w-12 h-12 rounded-lg bg-gray-50 overflow-hidden border border-gray-100 flex-shrink-0">
                                    <img :src="item.image" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-grow">
                                    <h4 class="text-xs font-bold text-gray-800 line-clamp-2 group-hover:text-blue-600 transition" x-text="item.name"></h4>
                                    <span class="text-[10px] font-bold text-gray-500 block mt-1" x-text="item.category"></span>
                                </div>
                            </a>
                        </template>
                    </div>

                    <div x-show="query.length >= 2 && results.length === 0 && !loading" style="display: none;" class="absolute top-full right-0 mt-3 w-[350px] bg-white rounded-xl shadow-2xl border border-gray-100 p-4 text-center">
                        <span class="text-xs font-bold text-gray-500">Produk tidak ditemukan.</span>
                    </div>
                </div>
            </div>
            
            <a href="{{ route('wishlist.index') }}" class="relative flex items-center hover:text-blue-600 transition-colors py-2"
            x-data="{ count: {{ auth()->check() ? auth()->user()->wishlists()->count() : 0 }} }"
            @wishlist-updated.window="count = $event.detail">
            
                <i class="fa-regular fa-heart cursor-pointer text-xl md:text-2xl"></i>
                
                <span x-show="count > 0" x-text="count" x-cloak
                    class="absolute -top-2 -right-3 bg-red-500 text-white text-[10px] md:text-xs font-bold w-5 h-5 md:w-6 md:h-6 rounded-full flex items-center justify-center border border-black shadow-sm transition-all duration-300 transform"
                    x-transition:enter="scale-0" x-transition:enter-end="scale-100">
                </span>
            </a>

            <div class="relative group z-50" 
                x-data="{ 
                    openCart: false,
                    cartCount: {{ auth()->check() ? auth()->user()->carts()->sum('quantity') : 0 }},
                    checkoutItems: {{ json_encode($miniCartData) }},
                    
                    get miniTotal() {
                        return this.checkoutItems.reduce((acc, item) => acc + (item.price * item.qty), 0);
                    },
                    formatRupiah(number) {
                        return 'Rp' + new Intl.NumberFormat('id-ID').format(number);
                    },
                    // Fungsi soft delete hanya dari daftar checkout (Mini Cart)
                    removeFromCheckout(id) {
                        this.checkoutItems = this.checkoutItems.filter(item => item.id !== id);
                    }
                }" 
                @mouseenter="openCart = true" @mouseleave="openCart = false"
                @cart-updated.window="cartCount = $event.detail.count; checkoutItems = $event.detail.items">
                
                <a href="{{ route('cart.index') }}" class="flex items-center relative hover:text-blue-600 transition-colors py-2" @click.prevent="openCart = !openCart">
                    <i class="fa-solid fa-cart-shopping cursor-pointer text-xl md:text-2xl"></i>
                    <span x-show="cartCount > 0" x-text="cartCount" x-cloak
                        class="absolute -top-2 -right-3 bg-yellow-400 text-black text-[10px] md:text-xs font-bold w-5 h-5 md:w-6 md:h-6 rounded-full flex items-center justify-center border border-black shadow-sm transition-all duration-300 transform">
                    </span>
                </a>

                <div x-show="openCart" @click.away="openCart = false" x-cloak
                    class="absolute right-0 mt-0 w-[300px] md:w-[350px] bg-white shadow-2xl border border-gray-100 rounded-xl z-50 p-4 md:p-6 transition-all duration-300">
                    
                    <div class="space-y-4 max-h-60 overflow-y-auto mb-4 custom-scrollbar pr-2">
                        <template x-for="item in checkoutItems" :key="item.id">
                            <div class="flex gap-4 border-b border-gray-100 pb-4 relative group/item">
                                <div class="w-14 h-14 bg-white border border-gray-100 p-1 rounded-md flex-shrink-0">
                                    <img :src="item.img" class="w-full h-full object-contain">
                                </div>
                                <div class="flex-grow pr-6">
                                    <h4 class="text-xs font-bold text-gray-800 line-clamp-2" x-text="item.name"></h4>
                                    <div class="mt-1 text-[10px] text-gray-500 flex items-center flex-wrap gap-1.5">
                                        <span class="text-gray-700 font-bold border border-gray-200 px-1.5 rounded bg-gray-50 shadow-sm" x-text="item.variant_name"></span>
                                        
                                        <span class="text-gray-300">|</span>
                                        
                                        <span><span x-text="item.qty"></span> x</span> 
                                        <span class="bg-yellow-300 text-black font-bold px-1 rounded-sm shadow-sm" x-text="formatRupiah(item.price)"></span>
                                    </div>
                                </div>
                                <button @click="removeFromCheckout(item.id)" title="Hapus dari Checkout" class="absolute top-0 right-0 text-gray-300 hover:text-red-500 transition">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>
                        </template>
                        <div x-show="checkoutItems.length === 0" class="text-center text-xs text-gray-400 py-4">Tidak ada produk yang dipilih untuk checkout.</div>
                    </div>

                    <div class="border-t border-gray-100 pt-4">
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-gray-500 font-bold text-sm">Subtotal Checkout</span>
                            <span class="text-blue-600 font-bold text-lg" x-text="formatRupiah(miniTotal)"></span>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('cart.index') }}" class="flex-1 bg-gray-100 text-gray-700 text-center py-2.5 rounded-full text-[10px] md:text-xs font-bold hover:bg-gray-200 transition">Lihat Keranjang</a>
                            
                            <form action="{{ route('checkout.index') }}" method="GET" class="flex-1">
                                <template x-for="item in checkoutItems">
                                    <input type="hidden" name="selected_items[]" :value="item.id">
                                </template>
                                <button type="submit" class="w-full bg-blue-500 text-white text-center py-2.5 rounded-full text-[10px] md:text-xs font-bold hover:bg-blue-600 transition shadow-lg" :disabled="checkoutItems.length === 0">Checkout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div x-show="mobileMenuOpen" 
         x-transition.opacity 
         class="fixed inset-0 bg-black/50 z-[60] md:hidden" 
         @click="mobileMenuOpen = false" x-cloak></div>

    <div x-show="mobileMenuOpen"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-300 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         class="fixed top-0 left-0 h-full w-[80%] max-w-sm bg-white z-[70] shadow-2xl overflow-y-auto md:hidden flex flex-col" x-cloak>
        
        <div class="p-5 border-b flex justify-between items-center bg-gray-50">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-gray-800 rounded-full flex items-center justify-center text-white font-bold text-xs">MG</div>
                <span class="text-lg font-bold tracking-tighter text-gray-800 uppercase">Menu</span>
            </div>
            <button @click="mobileMenuOpen = false" class="text-gray-400 hover:text-red-500 text-xl transition">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="p-5 flex-grow flex flex-col font-bold text-gray-700 text-sm">
            
            <div class="mb-6 pb-6 border-b border-gray-100">
                @guest
                    <a href="/login-register" class="flex items-center gap-3 text-blue-600">
                        <i class="fa-solid fa-arrow-right-to-bracket text-lg"></i> Log in / Sign Up
                    </a>
                @endguest
                @auth
                    <a href="{{ route('profile.index') }}" class="flex items-center gap-3 text-blue-600">
                        <i class="fa-regular fa-user text-lg"></i> Hi, {{ strtok(Auth::user()->name, ' ') }}
                    </a>
                @endauth
            </div>
            <div class="mb-6 relative" x-data="{ 
                query: '', results: [], loading: false,
                search() {
                    if(this.query.length < 2) { this.results = []; return; }
                    this.loading = true;
                    fetch(`/api/produk/search?q=${this.query}`)
                        .then(res => res.json())
                        .then(data => { this.results = data; this.loading = false; });
                }
            }">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" x-model="query" @keyup.debounce.300ms="search" placeholder="Cari alat medis..." class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-10 py-3 text-sm focus:outline-none focus:border-blue-500">
                <i x-show="loading" class="fa-solid fa-spinner fa-spin absolute right-4 top-1/2 -translate-y-1/2 text-blue-500"></i>

                <div x-show="results.length > 0" class="mt-2 bg-white rounded-xl shadow-lg border border-gray-100 max-h-60 overflow-y-auto w-full custom-scrollbar">
                    <template x-for="item in results" :key="item.id">
                        <a :href="`/detail-produk/${item.id}`" class="flex items-center gap-3 p-3 border-b border-gray-50 hover:bg-gray-50 text-xs">
                            <img :src="item.image" class="w-8 h-8 rounded object-cover">
                            <span class="font-bold text-gray-800 line-clamp-1" x-text="item.name"></span>
                        </a>
                    </template>
                </div>
            </div>

            <div x-data="{ openSub: false }" class="border-b border-gray-100">
                <button @click="openSub = !openSub" class="flex justify-between items-center w-full py-3 hover:text-blue-600">
                    Blog <i class="fa-solid fa-chevron-down text-[10px] transition-transform" :class="openSub ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="openSub" x-transition class="pb-3 pl-4 space-y-3 font-medium text-gray-500 text-xs max-h-48 overflow-y-auto custom-scrollbar">
                    @forelse($blogCategories as $bCat)
                        <a href="{{ route('blog.index', ['kategori' => $bCat->slug]) }}" class="block hover:text-blue-600">{{ $bCat->name }}</a>
                    @empty
                        <span class="block text-gray-300 italic">Belum ada kategori</span>
                    @endforelse
                </div>
            </div>

            <a href="/" class="py-3 border-b border-gray-100 hover:text-blue-600">Beranda</a>
            
            <a href="{{ route('produk.index') }}" class="py-3 border-b border-gray-100 hover:text-blue-600 block">Produk</a>

            <div x-data="{ openSub: false }" class="border-b border-gray-100">
                <button @click="openSub = !openSub" class="flex justify-between items-center w-full py-3 hover:text-blue-600">
                    Kategori <i class="fa-solid fa-chevron-down text-[10px] transition-transform" :class="openSub ? 'rotate-180' : ''"></i>
                </button>
                
                <div x-show="openSub" x-transition class="pb-3 pl-4 space-y-4 font-medium text-gray-500 text-xs mt-2 max-h-60 overflow-y-auto custom-scrollbar">
                    
                    @foreach($navCategories as $cat)
                    <div>
                        <span class="text-gray-800 font-bold uppercase block mb-2">{{ $cat->name }}</span>
                        <div class="space-y-2 pl-2 border-l border-gray-200">
                            @forelse($cat->products as $prod)
                            <a href="{{ route('produk.show', $prod->id) }}" class="block hover:text-blue-600">{{ $prod->name }}</a>
                            @empty
                            <span class="text-gray-300 italic text-[10px] block">Belum ada produk</span>
                            @endforelse
                        </div>
                    </div>
                    @endforeach
                    
                </div>
            </div>

            <div x-data="{ openSub: false }" class="border-b border-gray-100">
                <button @click="openSub = !openSub" class="flex justify-between items-center w-full py-3 hover:text-blue-600">
                    Blog <i class="fa-solid fa-chevron-down text-[10px] transition-transform" :class="openSub ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="openSub" x-transition class="pb-3 pl-4 space-y-3 font-medium text-gray-500 text-xs">
                    <a href="/blog" class="block hover:text-blue-600">Kesehatan Terbaru</a>
                    <a href="/blog" class="block hover:text-blue-600">Tips Medis</a>
                </div>
            </div>

            <a href="/kontak" class="py-3 hover:text-blue-600">Kontak</a>
        </div>
    </div>
</header>