<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Page;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class HomeSettingController extends Controller
{
    // 1. Tampilkan Semua Pengaturan di Satu Halaman
    public function index()
    {
        $banners = Banner::latest()->get();
        
        // Ambil data halaman 'Tentang Kami', kalau belum ada di DB, buatkan instance kosong sementara
        $aboutUs = Page::firstOrCreate(
            ['slug' => 'tentang-kami'],
            ['title' => 'Tentang Kami', 'content' => '', 'excerpt' => '']
        );

        // Ambil produk yang dicentang Flash Sale beserta variannya untuk harga coret
        $flashSaleProducts = Product::where('is_flash_sale', 1)->with('variants')->get();

        return view('admin.home.index', compact('banners', 'aboutUs', 'flashSaleProducts'));
    }

    // 2. Update Konten Zig-Zag "Tentang Kami"
    public function updateAbout(Request $request)
    {
        $request->validate([
            'excerpt' => 'nullable|string',
            'content' => 'nullable|string',
            'image_1' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_2' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $page = Page::where('slug', 'tentang-kami')->first();

        // Update teks
        $page->excerpt = $request->excerpt;
        $page->content = $request->content;

        // Proses Upload Gambar Kiri Bawah
        if ($request->hasFile('image_1')) {
            if ($page->image_1 && File::exists(public_path('images/' . $page->image_1))) {
                File::delete(public_path('images/' . $page->image_1));
            }
            $name1 = 'about_kiri_' . time() . '.' . $request->image_1->extension();
            $request->image_1->move(public_path('images'), $name1);
            $page->image_1 = $name1;
        }

        // Proses Upload Gambar Kanan Atas
        if ($request->hasFile('image_2')) {
            if ($page->image_2 && File::exists(public_path('images/' . $page->image_2))) {
                File::delete(public_path('images/' . $page->image_2));
            }
            $name2 = 'about_kanan_' . time() . '.' . $request->image_2->extension();
            $request->image_2->move(public_path('images'), $name2);
            $page->image_2 = $name2;
        }

        $page->save();

        return back()->with([
            'success' => 'Desain Tentang Kami berhasil diperbarui!',
            'tab' => 'about'
        ]);
    }

    // 3. Simpan Banner Baru
    public function storeBanner(Request $request)
    {
        $request->validate([
            'image_path' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'link_url' => 'nullable|string',
        ]);

        $imageName = 'banner_' . time() . '.' . $request->image_path->extension();
        $request->image_path->move(public_path('images'), $imageName);

        Banner::create([
            'image_path' => $imageName,
            'link_url' => $request->link_url,
            'is_active' => 1,
        ]);

        return back()->with('success', 'Banner promosi berhasil ditambahkan!');
    }

    // 4. Hapus Banner
    public function destroyBanner($id)
    {
        $banner = Banner::findOrFail($id);
        
        if (File::exists(public_path('images/' . $banner->image_path))) {
            File::delete(public_path('images/' . $banner->image_path));
        }
        
        $banner->delete();

        return back()->with('success', 'Banner berhasil dihapus permanen!');
    }
}
