<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    // Relasi ke tabel blog_posts
    public function posts()
    {
        return $this->hasMany(BlogPost::class, 'blog_category_id');
    }
}
