<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        // Data Info Kontak (Bisa dipindah ke sini agar mudah diedit kedepannya)
        $contactInfo = [
            'address' => "Jl. Muwuh, Sumberagung, Plaosan.<br>Magetan",
            'phone' => ['082332116115', '085784899882'],
            'hours' => [
                'Senin - Jumat: 09:00 - 20:00',
                'Sabtu & Minggu: 10:30 - 22:00'
            ]
        ];

        return view('kontak', compact('contactInfo'));
    }

    // Nanti kalau mau bikin fitur kirim pesan beneran, tambah fungsi ini:
    /*
    public function store(Request $request)
    {
        // Logika kirim email...
    }
    */
}