<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'variant_name', 'sku', 'price', 'stock', 'weight_gram'];

    // TARUH DI SINI:
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}