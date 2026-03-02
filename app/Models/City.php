<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    // Tambahkan 'shipping_cost' ke sini Bos!
    protected $fillable = ['province_id', 'type', 'name'];

    public function province()
    {
        return $this->belongsTo(Province::class);
    }
}
