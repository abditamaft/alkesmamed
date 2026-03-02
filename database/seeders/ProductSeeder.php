<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductImage;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // 1. Bersihkan HANYA tabel produk agar tidak numpuk jika dijalankan berulang
        // (Tabel cities dan provinces AMAN SENTOSA!)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        ProductImage::truncate();
        ProductVariant::truncate();
        Product::truncate();
        Category::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Tentukan 5 Kategori Sesuai Desain Header Anda
        $categories = [
            'Medical Accessories',
            'Face Mask',
            'Hospital Equipment',
            'Blood Pressure',
            'Home Medical Supplies'
        ];

        foreach ($categories as $catName) {
            // Buat Kategori
            $category = Category::create([
                'name' => $catName,
                'slug' => Str::slug($catName),
                'image' => 'gambar_hero.jpg' // Dummy image
            ]);

            // 3. Buat 4 Produk untuk masing-masing Kategori (Total 20 Produk)
            for ($i = 1; $i <= 4; $i++) {
                $prodName = 'Alat Kesehatan Mamed ' . $catName . ' Seri ' . $i;
                $product = Product::create([
                    'category_id' => $category->id,
                    'name' => $prodName,
                    'slug' => Str::slug($prodName) . '-' . rand(100, 999),
                    'description' => 'Ini adalah deskripsi detail untuk produk ' . $prodName . '. Diformulasikan khusus untuk standar medis, aman digunakan, dan telah lolos evaluasi uji klinis standar internasional.',
                    'is_active' => 1
                ]);

                // 4. Buat 5 Varian per Produk (Total 100 Varian)
                $variantNames = ['Small', 'Medium', 'Large', 'Extra Large', 'Custom Size'];
                foreach ($variantNames as $index => $vName) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'sku' => strtoupper(Str::random(3)) . '-' . $product->id . '-' . ($index + 1),
                        'variant_name' => $vName,
                        'price' => rand(50, 500) * 1000, // Harga acak Rp 50.000 - Rp 500.000
                        'stock' => rand(15, 100), // Stok acak
                        'weight_gram' => rand(200, 1500) // Berat acak 200g - 1.5kg
                    ]);
                }

                // 5. Buat 4 Gambar per Produk (Total 80 Gambar)
                for ($j = 1; $j <= 4; $j++) {
                    ProductImage::create([
                        'product_id' => $product->id,
                        // Kita pakai 'gambar_hero.jpg' yang sudah ada di folder Anda agar web tidak error cari gambar
                        'image_path' => 'gambar_hero.jpg', 
                        'is_main' => ($j == 1) ? 1 : 0 // Gambar urutan pertama otomatis jadi Cover/Main Image
                    ]);
                }
            }
        }

        $this->command->info('Boom! 5 Kategori, 20 Produk, 100 Varian, dan 80 Gambar berhasil diciptakan!');
    }
}