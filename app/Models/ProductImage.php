<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    // 👇 TAMBAHKAN BARIS INI 👇
    protected $fillable = ['product_id', 'image_path', 'is_main'];

    // Relasi balik ke Product (opsional tapi bagus untuk standar)
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}