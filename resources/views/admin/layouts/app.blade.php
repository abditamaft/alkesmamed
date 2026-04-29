<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Alkes Mamed</title>
    @vite('resources/css/app.css') 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #475569; border-radius: 10px; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased" x-data="{ sidebarOpen: true }">

    <div class="flex h-screen overflow-hidden">
        
        <aside :class="sidebarOpen ? 'w-64' : 'w-0 lg:w-20'" class="bg-slate-900 text-white transition-all duration-300 flex flex-col relative z-20 shadow-xl overflow-hidden flex-shrink-0">
            
            <div class="h-16 flex items-center justify-center border-b border-slate-800 px-4">
                <span class="font-black text-xl tracking-wider text-blue-400" x-show="sidebarOpen">ALKES<span class="text-white">MAMED</span></span>
                <i class="fa-solid fa-notes-medical text-2xl text-blue-500" x-show="!sidebarOpen" style="display: none;"></i>
            </div>

            <nav class="flex-1 overflow-y-auto custom-scrollbar py-4 px-3 space-y-1">
                
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'bg-transparent text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-chart-pie w-5 text-center"></i>
                    <span x-show="sidebarOpen">Dashboard</span>
                </a>

                <div class="pt-4 pb-1">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider px-3" x-show="sidebarOpen">Toko & Penjualan</p>
                </div>

                <div x-data="{ 
                    orderCount: 0,
                    fetchBadge() {
                        fetch('{{ route('admin.orders.badge') }}')
                            .then(res => res.json())
                            .then(data => this.orderCount = data.count);
                    },
                    init() {
                        this.fetchBadge(); 
                        setInterval(() => this.fetchBadge(), 30000); 
                    }
                }">
                    <a href="{{ route('admin.orders.index') }}" class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg transition group {{ request()->routeIs('admin.orders.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-clipboard-list w-5 text-center transition {{ request()->routeIs('admin.orders.*') ? 'text-white' : 'group-hover:text-blue-400' }}"></i>
                            <span x-show="sidebarOpen">Semua Pesanan</span>
                        </div>
                        <span x-show="orderCount > 0 && sidebarOpen" x-text="orderCount" x-transition 
                            class="bg-red-500 text-white text-[10px] font-black px-2 py-0.5 rounded-full shadow-lg shadow-red-500/50 transform group-hover:scale-110 transition" style="display: none;">
                        </span>
                    </a>
                </div>

                <div x-data="{ openMenu: {{ request()->routeIs('admin.products.*', 'admin.categories.*') ? 'true' : 'false' }} }">
                    <button @click="openMenu = !openMenu" class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg transition group {{ request()->routeIs('admin.products.*', 'admin.categories.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-box-open w-5 text-center transition {{ request()->routeIs('admin.products.*', 'admin.categories.*') ? 'text-blue-400' : 'group-hover:text-blue-400' }}"></i>
                            <span x-show="sidebarOpen">Katalog Produk</span>
                        </div>
                        <i x-show="sidebarOpen" class="fa-solid fa-chevron-down text-[10px] transition-transform" :class="openMenu ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="openMenu && sidebarOpen" x-collapse class="pl-11 pr-3 py-1 space-y-1">
                        <a href="{{ route('admin.products.index') }}" class="block py-2 text-sm transition {{ request()->routeIs('admin.products.*') ? 'text-blue-400 font-bold' : 'text-slate-400 hover:text-white' }}">• Semua Produk</a>
                        <a href="{{ route('admin.categories.index') }}" class="block py-2 text-sm transition {{ request()->routeIs('admin.categories.*') ? 'text-blue-400 font-bold' : 'text-slate-400 hover:text-white' }}">• Kategori</a>
                    </div>
                </div>

                <div class="pt-4 pb-1">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider px-3" x-show="sidebarOpen">Konten Web</p>
                </div>

                <a href="{{ route('admin.home.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition group {{ request()->routeIs('admin.home.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-desktop w-5 text-center transition {{ request()->routeIs('admin.home.*') ? 'text-white' : 'group-hover:text-blue-400' }}"></i>
                    <span x-show="sidebarOpen">Manajemen Beranda</span>
                </a>

                <div x-data="{ openMenu: {{ request()->routeIs('admin.blogs.*', 'admin.blog_categories.*') ? 'true' : 'false' }} }" class="mt-1">
                    <button @click="openMenu = !openMenu" class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg transition group {{ request()->routeIs('admin.blogs.*', 'admin.blog_categories.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-newspaper w-5 text-center transition {{ request()->routeIs('admin.blogs.*', 'admin.blog_categories.*') ? 'text-blue-400' : 'group-hover:text-blue-400' }}"></i>
                            <span x-show="sidebarOpen">Artikel / Blog</span>
                        </div>
                        <i x-show="sidebarOpen" class="fa-solid fa-chevron-down text-[10px] transition-transform" :class="openMenu ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="openMenu && sidebarOpen" x-collapse class="pl-11 pr-3 py-1 space-y-1">
                        <a href="{{ route('admin.blogs.index') }}" class="block py-2 text-sm transition {{ request()->routeIs('admin.blogs.*') ? 'text-blue-400 font-bold' : 'text-slate-400 hover:text-white' }}">• Semua Artikel</a>
                        <a href="{{ route('admin.blog_categories.index') }}" class="block py-2 text-sm transition {{ request()->routeIs('admin.blog_categories.*') ? 'text-blue-400 font-bold' : 'text-slate-400 hover:text-white' }}">• Kategori Blog</a>
                    </div>
                </div>

                <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition group mt-4 text-slate-300 hover:bg-slate-800 hover:text-white">
                    <i class="fa-solid fa-users w-5 text-center group-hover:text-blue-400 transition"></i>
                    <span x-show="sidebarOpen">Pelanggan</span>
                </a>

                <div class="pt-6 pb-1">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider px-3" x-show="sidebarOpen">Pengaturan Sistem</p>
                </div>
                
                <a href="{{ route('admin.shipping.index') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg transition group w-full {{ request()->routeIs('admin.shipping.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-truck-fast w-5 text-center transition {{ request()->routeIs('admin.shipping.*') ? 'text-white' : 'group-hover:text-blue-400' }}"></i>
                        <span x-show="sidebarOpen">Ongkos Kirim</span>
                    </div>
                </a>

            </nav>

            <div class="p-4 border-t border-slate-800 bg-slate-900">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center font-bold text-white flex-shrink-0">
                        AF
                    </div>
                    <div x-show="sidebarOpen" class="overflow-hidden">
                        <p class="text-sm font-bold text-white truncate">Admin Fitra</p>
                        <p class="text-[10px] text-slate-400 truncate">Super Admin</p>
                    </div>
                </div>
            </div>
        </aside>

        <div class="flex-1 flex flex-col h-screen overflow-hidden">
            
            <header class="h-16 bg-white shadow-sm flex items-center justify-between px-4 lg:px-8 z-10">
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-blue-600 transition p-2 rounded-lg hover:bg-gray-50">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>

                <div class="flex items-center gap-4">
                    <a href="/" target="_blank" class="text-sm font-bold text-gray-500 hover:text-blue-600 transition flex items-center gap-2">
                        <i class="fa-solid fa-globe"></i> Lihat Toko
                    </a>
                    <div class="h-6 w-px bg-gray-200"></div>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="text-sm font-bold text-red-500 hover:text-red-700 transition flex items-center gap-2">
                            <i class="fa-solid fa-right-from-bracket"></i> Keluar
                        </button>
                    </form>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto bg-[#f8f9fa] p-4 lg:p-8">
                @yield('content')
            </main>

        </div>
    </div>
</body>
</html>