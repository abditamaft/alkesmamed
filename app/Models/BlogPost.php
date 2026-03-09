<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'blog_category_id', 'user_id', 'title', 'slug', 
        'content', 'image_path', 'views', 'is_published'
    ];

    // Relasi ke Kategori
    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    // Relasi ke Penulis (Admin)
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}