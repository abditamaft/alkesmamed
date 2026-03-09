<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class BlogPostController extends Controller
{
    // 1. TAMPILKAN SEMUA ARTIKEL (DENGAN FITUR FILTER PENCARIAN)
    public function index(Request $request)
    {
        $query = BlogPost::with(['category', 'author'])->latest();

        // Tangkap kata kunci jika Admin menekan Enter
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhereHas('category', function($q) use ($request) {
                      $q->where('name', 'like', '%' . $request->search . '%');
                  });
        }

        // withQueryString() agar pagination tidak menghilangkan filter
        $posts = $query->paginate(10)->withQueryString();
        
        return view('admin.blogs.index', compact('posts'));
    }

    // 2. FORM TAMBAH ARTIKEL (Editor Fleksibel)
    public function create()
    {
        $categories = BlogCategory::all();
        return view('admin.blogs.create', compact('categories'));
    }

    // 3. PROSES SIMPAN
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'blog_category_id' => 'required|exists:blog_categories,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048', // Cover Utama
            'content' => 'required', // Ini akan berisi HTML dari Text Editor
        ]);

        // Upload Cover
        $imageName = time() . '-' . uniqid() . '.' . $request->file('image')->extension();
        $request->file('image')->move(public_path('images'), $imageName);

        BlogPost::create([
            'user_id' => Auth::id(), // ID Admin yang sedang login
            'blog_category_id' => $request->blog_category_id,
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . time(),
            'content' => $request->content,
            'image_path' => $imageName,
            'is_published' => $request->is_published,
            'views' => 0, // Default 0
        ]);

        return redirect()->route('admin.blogs.index')->with('success', 'Artikel berhasil dipublikasikan!');
    }

    // 4. FORM EDIT
    public function edit($id)
    {
        $post = BlogPost::findOrFail($id);
        $categories = BlogCategory::all();
        return view('admin.blogs.edit', compact('post', 'categories'));
    }

    // 5. PROSES UPDATE
    public function update(Request $request, $id)
    {
        $post = BlogPost::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'blog_category_id' => 'required|exists:blog_categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'content' => 'required',
        ]);

        $data = [
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . time(),
            'blog_category_id' => $request->blog_category_id,
            'content' => $request->content,
            'is_published' => $request->is_published,
        ];

        // Jika Admin ganti Cover
        if ($request->hasFile('image')) {
            // Hapus cover lama
            if (file_exists(public_path('images/' . $post->image_path))) {
                unlink(public_path('images/' . $post->image_path));
            }
            // Upload cover baru
            $imageName = time() . '-' . uniqid() . '.' . $request->file('image')->extension();
            $request->file('image')->move(public_path('images'), $imageName);
            $data['image_path'] = $imageName;
        }

        $post->update($data);
        return redirect()->route('admin.blogs.index')->with('success', 'Artikel berhasil diperbarui!');
    }

    // 6. HAPUS ARTIKEL
    public function destroy($id)
    {
        $post = BlogPost::findOrFail($id);
        if (file_exists(public_path('images/' . $post->image_path))) {
            unlink(public_path('images/' . $post->image_path));
        }
        $post->delete();
        return back()->with('success', 'Artikel berhasil dihapus!');
    }

    // 7. TOGGLE STATUS PUBLISH/DRAFT (AJAX)
    public function toggleStatus($id)
    {
        $post = BlogPost::findOrFail($id);
        $post->is_published = !$post->is_published;
        $post->save();
        return response()->json(['success' => true, 'is_published' => $post->is_published]);
    }

    // 8. LIVE SEARCH SIDEBAR ADMIN
    public function searchAdmin(Request $request)
    {
        $q = $request->get('q');
        $posts = BlogPost::with('category')
                    ->where('title', 'like', "%{$q}%")
                    ->take(5)
                    ->get();
        return response()->json($posts);
    }
}