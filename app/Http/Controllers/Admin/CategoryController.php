<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->latest()->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048' // Validasi file gambar maksimal 2MB
        ]);

        $imageName = null;
        if ($request->hasFile('image')) {
            // Buat nama unik dan pindahkan ke folder public/images
            $imageName = time() . '-' . Str::slug($request->name) . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
        }

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'image' => $imageName
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori dan gambar berhasil ditambahkan!');
    }

    // Menampilkan form edit
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    // Proses update data dan gambar
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        $category->name = $request->name;
        $category->slug = Str::slug($request->name);

        // Jika Admin mengupload gambar baru
        if ($request->hasFile('image')) {
            // 1. Hapus gambar lama dari folder jika ada
            if ($category->image && file_exists(public_path('images/' . $category->image))) {
                unlink(public_path('images/' . $category->image));
            }
            // 2. Upload gambar baru
            $imageName = time() . '-' . Str::slug($request->name) . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $category->image = $imageName;
        }

        $category->save();

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Kategori tidak bisa dihapus karena masih memiliki produk!');
        }

        // Hapus file gambar fisik dari folder public/images agar tidak menumpuk menjadi sampah
        if ($category->image && file_exists(public_path('images/' . $category->image))) {
            unlink(public_path('images/' . $category->image));
        }

        $category->delete();
        return back()->with('success', 'Kategori dan gambarnya berhasil dihapus!');
    }
}