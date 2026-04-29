<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    // Tambahkan baris ini juga Bos!
    protected $fillable = [
        'title',
        'image_path', 
        'link_url', 
        'is_active'
    ];
}