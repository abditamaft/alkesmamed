<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Jika belum login, ATAU sudah login tapi rolenya BUKAN admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect()->route('admin.login')->with('error', 'Silakan login sebagai Administrator terlebih dahulu.');
        }

        // Jika dia admin asli, silakan lewat!
        return $next($request);
    }
}