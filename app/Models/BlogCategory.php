<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{
    protected $guarded = [];

    // Relasi: 1 Kategori punya banyak Artikel
    public function posts()
    {
        return $this->hasMany(BlogPost::class);
    }
}
