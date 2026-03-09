<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    // Tampilkan Halaman Login
    public function showLoginForm()
    {
        // Jika sudah login dan dia admin, langsung lempar ke dashboard
        if (Auth::check() && Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.auth.login');
    }

    // Proses Cek Login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Coba melakukan login
        if (Auth::attempt($credentials, $request->remember)) {
            
            // CEK ROLE: Apakah dia benar-benar Admin?
            if (Auth::user()->role === 'admin') {
                $request->session()->regenerate();
                return redirect()->intended(route('admin.dashboard'));
            }

            // Jika bukan admin (misal customer iseng), TENDANG!
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return back()->with('error', 'Akses Ditolak! Anda bukan Administrator.');
        }

        // Jika email/password salah
        return back()->with('error', 'Email atau kata sandi salah!');
    }

    // Proses Logout Admin
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('admin.login');
    }
}