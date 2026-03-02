<footer x-data="{ shown: false }" 
        x-intersect="shown = true"
        :class="shown ? 'translate-y-0 opacity-100' : 'translate-y-20 opacity-0'"
        class="mt-16 md:mt-20 border-t bg-[#f5f5f5] pt-12 md:pt-16 pb-8 px-4 md:px-10 transition-all duration-1000 ease-out">
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 md:gap-12 text-center md:text-left">
        
        <div class="flex flex-col items-center md:items-start">
            <div class="flex items-center gap-2 mb-4 md:mb-6">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-gray-800 rounded-full flex items-center justify-center text-white font-bold text-lg md:text-xl">MG</div>
                <span class="text-xl md:text-2xl font-bold uppercase">Alkes Mamed</span>
            </div>
            <p class="text-sm text-gray-500 leading-relaxed mb-4">
                Copyright © 2026 HasThemes | <br class="md:hidden"><span class="text-blue-600 font-semibold">Built with Medizin by TIF24.</span>
            </p>
        </div>

        <div>
            <h5 class="text-base md:text-lg font-bold mb-4 md:mb-6">Belanja Via Marketplace</h5>
            <ul class="text-gray-600 space-y-2 md:space-y-3 font-medium text-sm md:text-base">
                <li><a href="#" class="hover:text-blue-600 transition">Shopee</a></li>
                <li><a href="#" class="hover:text-blue-600 transition">Tokopedia</a></li>
                <li><a href="#" class="hover:text-blue-600 transition">Blibli</a></li>
                <li><a href="#" class="hover:text-blue-600 transition">Lazada</a></li>
            </ul>
        </div>

        <div>
            <h5 class="text-base md:text-lg font-bold mb-4 md:mb-6">Bantuan Pelanggan</h5>
            <ul class="text-gray-600 space-y-2 md:space-y-3 font-medium text-sm md:text-base">
                <li><a href="#" class="hover:text-blue-600 uppercase transition">Kontak HULWA</a></li>
                <li><a href="#" class="hover:text-blue-600 uppercase transition">Lokasi HULWA</a></li>
            </ul>
        </div>

        <div class="flex flex-col items-center md:items-start">
            <h5 class="text-base md:text-lg font-bold mb-4 md:mb-6">Ikuti dan Ulas ALKES MAMED</h5>
            <div class="flex gap-4 md:gap-5">
                <a href="#" class="text-3xl md:text-3xl text-pink-600 hover:scale-110 transition-transform"><i class="fa-brands fa-instagram"></i></a>
                <a href="#" class="text-3xl md:text-3xl text-red-600 hover:scale-110 transition-transform"><i class="fa-brands fa-youtube"></i></a>
                <a href="#" class="text-3xl md:text-3xl text-blue-600 hover:scale-110 transition-transform"><i class="fa-brands fa-facebook"></i></a>
            </div>
        </div>
    </div>

    <div class="mt-12 md:mt-16 pt-6 md:pt-8 border-t border-gray-200 text-center text-gray-400 text-xs md:text-sm">
        &copy; 2026 Alkes Mamed - All Rights Reserved.
    </div>
</footer>

<script src="https://unpkg.com/@alpinejs/intersect@3.x.x/dist/cdn.min.js"></script>