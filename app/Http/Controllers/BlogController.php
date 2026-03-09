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

    public function show($id)
    {
        // 1. Cari artikel beserta relasi kategori dan penulisnya (admin)
        $article = BlogPost::with(['category', 'author'])->findOrFail($id);
        
        // 2. Tambah jumlah view (VERSI PRO: ANTI SPAM REFRESH) 🔥
        $sessionKey = 'blog_viewed_' . $article->id;
        if (!session()->has($sessionKey)) {
            $article->increment('views');      // Nambah ke database
            session()->put($sessionKey, true); // Kunci gembok session-nya
        }

        // 3. Ambil data untuk Sidebar
        $categories = BlogCategory::withCount(['posts' => function($q) {
            $q->where('is_published', 1);
        }])->get();

        $popular_posts = BlogPost::where('is_published', 1)
                                 ->orderBy('views', 'desc')
                                 ->take(3)
                                 ->get();

        // 4. Ambil 6 Artikel Serupa (kategori sama, kecuali artikel yang sedang dibaca)
        $related_posts = BlogPost::where('blog_category_id', $article->blog_category_id)
                                 ->where('id', '!=', $article->id)
                                 ->where('is_published', 1)
                                 ->inRandomOrder()
                                 ->take(6)
                                 ->get();

        return view('detail-blog', compact('article', 'popular_posts', 'categories', 'related_posts'));
    }
}