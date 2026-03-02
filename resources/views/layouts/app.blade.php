<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ALKES MAMED - Home Medical Supplies</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/@alpinejs/intersect@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        [x-cloak] { display: none !important; }
        
        /* Animasi Sticky Header */
        .sticky-header {
            animation: slideDown 0.5s ease;
        }
        @keyframes slideDown {
            from { transform: translateY(-100%); }
            to { transform: translateY(0); }
        }

        /* Animasi Masuk Halaman (Fade In Up) */
        .page-enter {
            animation: fadeInUp 0.8s ease-out forwards;
        }
        @keyframes fadeInUp {
            from { 
                opacity: 0; 
                transform: translateY(30px); 
            }
            to { 
                opacity: 1; 
                transform: translateY(0); 
            }
        }

        .hover-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .hover-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1);
        }
    </style>
</head>
<body class="bg-gray-50 font-sans overflow-x-hidden">
    <div x-data="{ showToast: false, timeout: null }"
         @cart-updated.window="
            showToast = true;
            clearTimeout(timeout);
            timeout = setTimeout(() => showToast = false, 4000); // Hilang otomatis dalam 4 detik
         "
         class="fixed top-24 right-4 md:top-28 md:right-10 z-[99999] flex flex-col gap-2"
         x-cloak>

         <div x-show="showToast"
              @mouseenter="clearTimeout(timeout)" 
              @mouseleave="timeout = setTimeout(() => showToast = false, 4000)"
              x-transition:enter="transition ease-out duration-300 transform"
              x-transition:enter-start="opacity-0 translate-x-full"
              x-transition:enter-end="opacity-100 translate-x-0"
              x-transition:leave="transition ease-in duration-200 transform"
              x-transition:leave-start="opacity-100 translate-x-0"
              x-transition:leave-end="opacity-0 translate-x-full"
              class="bg-white border-l-4 border-green-500 shadow-[0_10px_40px_rgba(0,0,0,0.15)] rounded-xl p-4 flex items-start gap-4 w-[300px] md:w-[350px] cursor-default">

              <div class="bg-green-100 text-green-500 rounded-full w-8 h-8 flex items-center justify-center flex-shrink-0 mt-0.5">
                  <i class="fa-solid fa-check text-sm"></i>
              </div>
              
              <div class="flex-grow">
                  <h4 class="text-sm font-bold text-gray-800">Berhasil ditambahkan!</h4>
                  <p class="text-xs text-gray-500 mt-1 line-clamp-2">Produk telah masuk ke keranjang belanja Anda.</p>
                  
                  <a href="/keranjang" class="inline-block mt-3 text-xs font-bold text-blue-600 hover:text-blue-800 transition">
                      Lihat Keranjang <i class="fa-solid fa-arrow-right-long ml-1"></i>
                  </a>
              </div>
              
              <button @click="showToast = false" class="text-gray-400 hover:text-red-500 transition mt-0.5">
                  <i class="fa-solid fa-xmark text-lg"></i>
              </button>
         </div>
    </div>

    @include('partials.header')

    <main class="pt-2 page-enter">
        @yield('content')
    </main>

    @include('partials.footer')

</body>
</html>