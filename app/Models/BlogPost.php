<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    protected $guarded = [];

    // Relasi: Artikel dimiliki oleh 1 Kategori
    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    // Relasi: Artikel ditulis oleh 1 User (Admin)
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
