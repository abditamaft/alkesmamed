<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable = ['province_id', 'type', 'name'];

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    // TAMBAHKAN RELASI INI: 1 Kota punya 1 Tarif Ongkir
    public function shippingRate()
    {
        return $this->hasOne(ShippingRate::class);
    }
}