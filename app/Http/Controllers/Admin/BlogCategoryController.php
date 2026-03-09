<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogCategoryController extends Controller
{
    public function index()
    {
        // Ambil semua kategori blog beserta jumlah artikel di dalamnya
        $categories = BlogCategory::withCount('posts')->orderBy('name')->get();
        return view('admin.blog_categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:blog_categories,name',
        ]);

        BlogCategory::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name), // Otomatis buat URL ramah SEO
        ]);

        return back()->with('success', 'Kategori Blog berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $category = BlogCategory::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255|unique:blog_categories,name,' . $id,
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return back()->with('success', 'Nama Kategori Blog berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $category = BlogCategory::withCount('posts')->findOrFail($id);

        // Proteksi: Jangan hapus jika sudah ada artikelnya
        if ($category->posts_count > 0) {
            return back()->with('error', 'Kategori ini tidak bisa dihapus karena masih memiliki artikel!');
        }

        $category->delete();
        return back()->with('success', 'Kategori Blog berhasil dihapus!');
    }
}