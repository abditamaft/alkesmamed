@extends('layouts.app')

@section('content')
<div class="bg-white min-h-screen flex items-center justify-center px-6 py-20" 
     x-data="{
        expiry: {{ $expiresAt->timestamp * 1000 }},
        minutes: '00',
        seconds: '00',
        expired: false,
        init() {
            // Hitung mundur setiap 1 detik
            setInterval(() => {
                let now = new Date().getTime();
                let distance = this.expiry - now;
                
                if (distance <= 0) {
                    this.expired = true;
                    this.minutes = '00';
                    this.seconds = '00';
                } else {
                    this.expired = false;
                    this.minutes = String(Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60))).padStart(2, '0');
                    this.seconds = String(Math.floor((distance % (1000 * 60)) / 1000)).padStart(2, '0');
                }
            }, 1000);
        }
     }">
     
    <div class="max-w-md w-full text-center page-enter">
        
        <div x-show="!expired" x-cloak>
            <div class="w-24 h-24 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner relative">
                <i class="fa-solid fa-envelope-open-text text-4xl"></i>
                <span class="absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-20 animate-ping"></span>
            </div>
            <h1 class="text-3xl font-extrabold text-gray-900 mb-4">Cek Email Anda</h1>
            <p class="text-gray-500 mb-6 leading-relaxed">
                Kami telah mengirimkan link verifikasi. Silakan klik link tersebut sebelum waktu habis.
            </p>
            
            <div class="bg-gray-50 border border-gray-200 rounded-2xl py-4 mb-8">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Link Kedaluwarsa Dalam</p>
                <div class="text-4xl font-black text-gray-800 tracking-widest font-mono">
                    <span x-text="minutes"></span>:<span x-text="seconds"></span>
                </div>
            </div>
        </div>

        <div x-show="expired" x-cloak style="display: none;">
            <div class="w-24 h-24 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                <i class="fa-solid fa-clock-rotate-left text-4xl"></i>
            </div>
            <h1 class="text-3xl font-extrabold text-gray-900 mb-4">Link Kedaluwarsa!</h1>
            <p class="text-red-500 mb-8 leading-relaxed font-medium bg-red-50 p-4 rounded-xl border border-red-100">
                Waktu 60 menit telah habis. Link verifikasi di email Anda sudah tidak dapat digunakan. Silakan minta link baru.
            </p>
        </div>

        @if (session('message'))
            <div class="bg-green-50 border border-green-200 text-green-600 px-4 py-3 rounded-xl mb-8 text-sm font-bold">
                {{ session('message') }}
            </div>
        @endif

        <div class="space-y-4">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" 
                        class="w-full text-white font-bold py-4 rounded-full transition shadow-lg"
                        :class="expired ? 'bg-red-500 hover:bg-red-600 shadow-red-500/30' : 'bg-blue-500 hover:bg-blue-600 shadow-blue-500/30'">
                    <span x-show="!expired">Kirim Ulang Link Verifikasi</span>
                    <span x-show="expired" style="display: none;"><i class="fa-solid fa-paper-plane mr-2"></i> Minta Link Baru Sekarang</span>
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-gray-400 text-sm font-bold hover:text-gray-800 transition">
                    Keluar dan Gunakan Email Lain
                </button>
            </form>
        </div>
    </div>
</div>

<style>
    .page-enter {
        opacity: 0;
        transform: translateY(20px);
        animation: fadeInUp 0.8s ease-out forwards;
    }
    @keyframes fadeInUp {
        to { opacity: 1; transform: translateY(0); }
    }
    /* Cloak untuk menyembunyikan elemen sebelum AlpineJS siap */
    [x-cloak] { display: none !important; }
</style>
@endsection