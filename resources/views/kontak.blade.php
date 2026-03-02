@extends('layouts.app')

@section('content')
<div class="bg-white min-h-screen">

    <div class="bg-[#f8f8f8] py-16 text-center mb-16 page-enter">
        <h1 class="text-4xl font-bold text-gray-900 mb-2">Kontak Kami</h1>
        <nav class="text-sm text-gray-500">
            <a href="/" class="hover:text-blue-600 transition">Beranda</a> / 
            <span class="text-gray-900 font-medium">Hubungi Kami</span>
        </nav>
    </div>

    <div class="max-w-7xl mx-auto px-6 mb-20">
        
        <div class="text-center max-w-2xl mx-auto mb-16" 
             x-data="{ shown: false }" x-intersect="shown = true" 
             :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'" 
             class="transition-all duration-700 ease-out">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Kami Selalu Ingin Mendengar Dari Anda!</h2>
            <p class="text-gray-500 leading-relaxed">
                Anda dapat menghubungi kami pada jam kerja atau kunjungi kantor kami. Semua email akan mendapatkan respon dalam waktu 24 jam. Senang mendengar dari Anda!
            </p>
        </div>

        <div class="grid grid-cols-3 gap-8 mb-20 text-center">
            
            <div x-data="{ shown: false }" x-intersect="shown = true" 
                 :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'" 
                 class="transition-all duration-700 delay-100 ease-out">
                <div class="w-16 h-16 mx-auto mb-6 text-gray-800">
                     <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 mx-auto">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Lokasi Kami</h3>
                <p class="text-gray-600 leading-relaxed">
                    {!! $contactInfo['address'] !!}
                </p>
            </div>

            <div x-data="{ shown: false }" x-intersect="shown = true" 
                 :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'" 
                 class="transition-all duration-700 delay-200 ease-out">
                <div class="w-16 h-16 mx-auto mb-6 text-gray-800">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 mx-auto">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Telepon Kami</h3>
                @foreach($contactInfo['phone'] as $phone)
                    <p class="text-gray-600">{{ $phone }}</p>
                @endforeach
            </div>

            <div x-data="{ shown: false }" x-intersect="shown = true" 
                 :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'" 
                 class="transition-all duration-700 delay-300 ease-out">
                <div class="w-16 h-16 mx-auto mb-6 text-gray-800">
                     <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 mx-auto">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Jam Operasional Kami</h3>
                @foreach($contactInfo['hours'] as $hour)
                    <p class="text-gray-600">{{ $hour }}</p>
                @endforeach
            </div>

        </div>

        <div class="w-full h-[450px] bg-gray-200 rounded-2xl overflow-hidden mb-20 relative group"
             x-data="{ shown: false }" x-intersect.margin.10%="shown = true" 
             :class="shown ? 'opacity-100 scale-100' : 'opacity-0 scale-95'" 
             class="transition-all duration-1000 ease-out shadow-lg">
             <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3954.498246872615!2d111.23727937583684!3d-7.633519692383832!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e799245199f7d43%3A0x63318536780c354e!2sJl.%20Muwuh%2C%20Sumberagung%2C%20Kec.%20Plaosan%2C%20Kabupaten%20Magetan%2C%20Jawa%20Timur!5e0!3m2!1sid!2sid!4v1700000000000!5m2!1sid!2sid" 
                width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                class="grayscale group-hover:grayscale-0 transition duration-700">
            </iframe>
        </div>

        <div class="text-center mb-16"
             x-data="{ shown: false }" x-intersect.margin.10%="shown = true" 
             :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'" 
             class="transition-all duration-700 ease-out">
            <h2 class="text-3xl font-bold text-gray-900 mb-12">Tanyakan Sesuatu Di Sini</h2>
            
            <form class="max-w-4xl mx-auto space-y-6">
                <div class="grid grid-cols-2 gap-6">
                    <input type="text" placeholder="Nama Depan" class="w-full bg-[#f8f8f8] border-0 rounded-full px-6 py-4 focus:ring-2 focus:ring-blue-500 outline-none transition">
                    <input type="email" placeholder="Email Anda" class="w-full bg-[#f8f8f8] border-0 rounded-full px-6 py-4 focus:ring-2 focus:ring-blue-500 outline-none transition">
                </div>
                <div class="grid grid-cols-2 gap-6">
                    <input type="text" placeholder="Nomor Telepon Anda" class="w-full bg-[#f8f8f8] border-0 rounded-full px-6 py-4 focus:ring-2 focus:ring-blue-500 outline-none transition">
                    <input type="text" placeholder="Subjek" class="w-full bg-[#f8f8f8] border-0 rounded-full px-6 py-4 focus:ring-2 focus:ring-blue-500 outline-none transition">
                </div>
                <textarea rows="6" placeholder="Pesan" class="w-full bg-[#f8f8f8] border-0 rounded-3xl px-6 py-4 focus:ring-2 focus:ring-blue-500 outline-none transition resize-none"></textarea>
                
                <button type="button" class="bg-blue-500 text-white font-bold px-10 py-4 rounded-full hover:bg-blue-600 transition transform hover:scale-105 shadow-lg shadow-blue-500/30 mt-4">
                    Kirim Pesan
                </button>
            </form>
        </div>

    </div>
</div>
@endsection