<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    public function run()
    {
        // 1. Pastikan ada minimal 1 user admin untuk jadi penulis
        $admin = User::firstOrCreate(
            ['email' => 'admin@alkesmamed.com'],
            ['name' => 'Admin Mamed', 'password' => bcrypt('password'), 'role' => 'admin']
        );

        // 2. Buat Kategori
        $categories = ['Kesehatan 5.0', 'Kehidupan Sehari-hari', 'Berita Hangat', 'Tips & Trik', 'Medis'];
        foreach ($categories as $cat) {
            BlogCategory::firstOrCreate([
                'name' => $cat,
                'slug' => Str::slug($cat)
            ]);
        }

        // 3. Buat 20 Artikel Dummy
        for ($i = 1; $i <= 20; $i++) {
            $title = "Artikel Kesehatan & Medis Seri ke-" . $i;
            BlogPost::create([
                'blog_category_id' => rand(1, 5),
                'user_id' => $admin->id,
                'title' => $title,
                'slug' => Str::slug($title) . '-' . time() . $i,
                'content' => '<p>Ini adalah konten panjang untuk artikel ' . $title . '. Mengalami pergeseran paradigma perubahan yang cepat, layanan kesehatan memasuki sistem digital yang canggih.</p>',
                'image_path' => 'gambar_berita.jpg', // Gambar default
                'views' => rand(10, 2000), // Views acak untuk ngetes sistem Ranking
                'is_published' => 1,
            ]);
        }
    }
}