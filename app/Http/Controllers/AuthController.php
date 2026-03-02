<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended('/profil')->with('success', 'Selamat datang kembali!');
        }

        return back()->withErrors(['email' => 'Email atau password yang Anda masukkan salah.'])->withInput();
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            // Validasi email agar HARUS UNIK (tidak boleh daftar 2x)
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ], [
            'email.unique' => 'Email ini sudah terdaftar. Silakan gunakan email lain atau langsung Login.'
        ]);

        // Buat User Baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Password di-enkripsi
            'role' => 'customer'
        ]);

        // Langsung Login-kan setelah sukses daftar
        Auth::login($user);

        return redirect('/profil')->with('success', 'Akun berhasil dibuat! Silakan lengkapi data profil Anda.');
    }

    // --- FITUR SSO GOOGLE ---
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Cek apakah user dengan email ini sudah ada di database
            $existingUser = User::where('email', $googleUser->email)->first();

            if ($existingUser) {
                // Jika sudah ada, update google_id-nya dan login-kan
                $existingUser->update(['google_id' => $googleUser->id]);
                Auth::login($existingUser);
            } else {
                // Jika belum ada, buat akun baru secara otomatis
                $newUser = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    // Buat password acak karena dia login pakai Google
                    'password' => Hash::make(Str::random(16)), 
                    'profile_picture' => $googleUser->avatar, // Ambil foto profil dari Google
                    'role' => 'customer'
                ]);
                Auth::login($newUser);
            }

            return redirect('/profil')->with('success', 'Berhasil login menggunakan Google!');

        } catch (\Exception $e) {
            return redirect('/login-register')->withErrors(['email' => 'Gagal login dengan Google. Silakan coba lagi.']);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}