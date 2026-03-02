<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;

    // TAMBAHKAN BARIS INI BOS!
    protected $fillable = [
        'user_id',
        'province_id',
        'city_id',
        'title',
        'address_line',
        'postal_code',
        'is_primary',
    ];

    // Relasi balik ke User (Opsional tapi direkomendasikan)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}