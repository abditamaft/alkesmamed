<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    // Tambahkan baris ini untuk memberi izin pengisian data!
    protected $fillable = [
        'title', 
        'slug', 
        'content', 
        'excerpt', 
        'image_1', 
        'image_2'
    ];
}