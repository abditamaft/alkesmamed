<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BlogPost;
use App\Models\BlogCategory;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        // 1. Siapkan Query Dasar (Hanya yang di-publish)
        $query = BlogPost::with(['category', 'author'])->where('is_published', 1);

        // 2. Filter Berdasarkan Kategori (Jika user klik kategori di sidebar)
        if ($request->has('kategori')) {
            $category = BlogCategory::where('slug', $request->kategori)->first();
            if ($category) {
                $query->where('blog_category_id', $category->id);
            }
        }

        // 3. Filter Berdasarkan Form Pencarian Manual
        if ($request->has('cari') && $request->cari != '') {
            $query->where('title', 'like', '%' . $request->cari . '%');
        }

        // 4. Eksekusi Query dengan Pagination (Max 40 per halaman)
        $posts = $query->orderBy('updated_at', 'desc')->paginate(40);

        // 5. Ambil Semua Kategori + Jumlah Artikel di dalamnya
        $categories = BlogCategory::withCount(['posts' => function($q) {
            $q->where('is_published', 1);
        }])->get();

        // 6. Ambil 3 Artikel Paling Banyak Dilihat (Ranking)
        $popular_posts = BlogPost::where('is_published', 1)
                                 ->orderBy('views', 'desc')
                                 ->take(3)
                                 ->get();

        return view('blog', compact('posts', 'categories', 'popular_posts'));
    }

    // FITUR AJAX: Sugesti Pencarian (Live Search)
    public function searchApi(Request $request)
    {
        $keyword = $request->q;
        $results = BlogPost::where('title', 'like', "%{$keyword}%")
                           ->where('is_published', 1)
                           ->select('id', 'title', 'slug', 'image_path')
                           ->take(5) // Tampilkan max 5 sugesti
                           ->get();
                           
        return response()->json($results);
    }

    public function show($id) // Nanti bisa diganti pakai $slug
    {
        $article = BlogPost::with(['category', 'author'])->findOrFail($id);
        
        // Tambah jumlah view setiap kali dibaca
        $article->increment('views');

        $popular_posts = BlogPost::where('is_published', 1)->orderBy('views', 'desc')->take(3)->get();
        return view('detail-blog', compact('article', 'popular_posts'));
    }
}