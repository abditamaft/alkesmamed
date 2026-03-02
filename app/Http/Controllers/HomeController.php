<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // 1. Kita pindahkan logika data (Array Produk) ke sini
        // Nanti di masa depan, data ini bisa diambil dari Database (Model)
        $products = [
            [
                'tag' => '-26%', 
                'cat' => 'PERSONAL', 
                'name' => 'Disposable Hand Wash Gel', 
                'price' => 'Rp. 336.000', 
                'old' => 'Rp. 453.000', 
                'sold' => 4, 
                'total' => 6
            ],
            [
                'tag' => 'HOT -38%', 
                'cat' => 'HOSPITAL EQUIPMENT', 
                'name' => 'Surgical Latex Gloves', 
                'price' => 'Rp. 168.000', 
                'old' => 'Rp. 268.000', 
                'sold' => 18, 
                'total' => 30
            ],
            [
                'tag' => 'HOT -20%', 
                'cat' => 'HOSPITAL EQUIPMENT', 
                'name' => 'Manual Oxygen Device', 
                'price' => 'Rp. 201.000', 
                'old' => 'Rp. 268.000', 
                'sold' => 9, 
                'total' => 10
            ],
            [
                'tag' => '-30%', 
                'cat' => 'HOSPITAL EQUIPMENT', 
                'name' => '12-Ply Gauze Sponges', 
                'price' => 'Rp. 117.000', 
                'old' => 'Rp. 168.000', 
                'sold' => 14, 
                'total' => 15
            ],
        ];

        // 2. Kirim data tersebut ke View 'home'
        // function compact('products') sama dengan ['products' => $products]
        return view('home', compact('products'));
    }
}
