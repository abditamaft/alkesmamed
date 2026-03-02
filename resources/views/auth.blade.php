@extends('layouts.app')

@section('content')
<div class="bg-white min-h-screen pt-12 pb-20 px-6">

    <div class="max-w-7xl mx-auto text-center mb-16 page-enter">
        <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-4 tracking-tight">Selamat Datang</h1>
        <nav class="text-sm text-gray-500 font-medium flex justify-center items-center gap-2">
            <a href="/" class="hover:text-blue-500 transition">Beranda</a> 
            <span class="text-gray-300">/</span> 
            <span class="text-gray-800">Login & Register</span>
        </nav>
    </div>

    @if($errors->any())
    <div class="max-w-7xl mx-auto mb-6">
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
            <strong class="font-bold">Oops!</strong>
            <span class="block sm:inline">{{ $errors->first() }}</span>
        </div>
    </div>
    @endif

    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-20">
        
        <div class="bg-[#f8f8f8] p-12 rounded-xl h-fit page-enter" style="animation-delay: 100ms;">
            <h2 class="text-3xl font-bold text-gray-900 mb-10">Login</h2>
            
            <form action="{{ route('login.perform') }}" method="POST" class="space-y-6">
                
                @csrf 

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Alamat Email *</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full bg-white border border-gray-200 rounded-full px-6 py-4 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition shadow-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Password *</label>
                    <input type="password" name="password" required
                           class="w-full bg-white border border-gray-200 rounded-full px-6 py-4 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition shadow-sm">
                </div>

                <div class="flex items-center justify-between text-sm text-gray-600 mt-4">
                    <label class="flex items-center gap-2 cursor-pointer hover:text-gray-900">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-blue-500 focus:ring-blue-500">
                        Ingat saya
                    </label>
                    <a href="#" class="text-blue-500 hover:underline">Lupa password anda?</a>
                </div>

                <button type="submit" class="w-full bg-blue-500 text-white font-bold py-4 rounded-full hover:bg-blue-600 transition transform hover:scale-[1.02] shadow-lg shadow-blue-500/30 mt-4">
                    Log in
                </button>
                <div class="mt-8">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-[#f8f8f8] text-gray-500 font-medium">Atau masuk dengan</span>
                        </div>
                    </div>

                    <a href="{{ route('google.login') }}" class="mt-6 w-full flex items-center justify-center gap-3 bg-white border border-gray-300 text-gray-700 font-bold py-3.5 rounded-full hover:bg-gray-50 transition shadow-sm">
                        <svg class="w-5 h-5" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        Google
                    </a>
                </div>
            </form>
        </div>

        <div class="p-4 page-enter" style="animation-delay: 300ms;">
            <h2 class="text-3xl font-bold text-gray-900 mb-10">Register</h2>
            
            <form action="{{ route('register.perform') }}" method="POST" class="space-y-6">
                
                @csrf

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Username *</label>
                    <input type="text" name="name" required
                           class="w-full bg-[#f8f8f8] border-0 rounded-full px-6 py-4 focus:ring-2 focus:ring-blue-500 outline-none transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Alamat Email *</label>
                    <input type="email" name="email" required
                           class="w-full bg-[#f8f8f8] border-0 rounded-full px-6 py-4 focus:ring-2 focus:ring-blue-500 outline-none transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Password *</label>
                    <input type="password" name="password" required
                           class="w-full bg-[#f8f8f8] border-0 rounded-full px-6 py-4 focus:ring-2 focus:ring-blue-500 outline-none transition">
                </div>

                <div class="text-sm text-gray-500 leading-relaxed">
                    Data pribadi Anda akan digunakan untuk mendukung pengalaman Anda di seluruh situs web ini...
                </div>

                <button type="submit" class="w-full bg-blue-500 text-white font-bold py-4 rounded-full hover:bg-blue-600 transition transform hover:scale-[1.02] shadow-lg shadow-blue-500/30">
                    Register
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
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endsection