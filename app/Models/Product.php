<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'name', 'slug', 'description', 'is_active'];

    // Relasi ke tabel Kategori
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relasi ke tabel Gambar
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    // Mengambil HANYA gambar utama (is_main = 1)
    public function mainImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_main', 1);
    }

    // Relasi ke tabel Varian (Harga & Stok)
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    // Fitur Cerdas: Mengambil harga termurah dari variannya
    public function getStartingPriceAttribute()
    {
        return $this->variants()->min('price');
    }
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }
}