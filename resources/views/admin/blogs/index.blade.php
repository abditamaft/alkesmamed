@extends('admin.layouts.app')

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-black text-gray-800">Semua Artikel</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola konten blog, tips, dan berita.</p>
    </div>
    
    <div class="flex items-center gap-4">
        <div class="relative" x-data="{ 
            query: '', results: [], loading: false,
            search() {
                if(this.query.length < 2) { this.results = []; return; }
                this.loading = true;
                fetch(`/admin/blogs/search?q=${this.query}`)
                    .then(res => res.json())
                    .then(data => { this.results = data; this.loading = false; });
            }
        }">
            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input type="text" x-model="query" @input.debounce.300ms="search" placeholder="Cari judul artikel..." class="bg-white border border-gray-200 rounded-xl pl-11 pr-4 py-2.5 text-sm focus:ring-1 focus:border-blue-500 outline-none w-64 shadow-sm">
            
            <div x-show="results.length > 0" @click.outside="results = []" class="absolute top-full left-0 mt-2 w-full bg-white rounded-xl shadow-2xl border border-gray-100 z-50 overflow-hidden">
                <template x-for="item in results" :key="item.id">
                    <a :href="`/admin/blogs/${item.id}/edit`" class="flex items-center gap-3 p-3 hover:bg-blue-50 border-b border-gray-50">
                        <div class="w-10 h-10 rounded bg-gray-100 overflow-hidden flex-shrink-0">
                            <img :src="`/images/${item.image_path}`" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-gray-800 line-clamp-1" x-text="item.title"></h4>
                            <span class="text-[10px] text-gray-500" x-text="item.category ? item.category.name : ''"></span>
                        </div>
                    </a>
                </template>
            </div>
        </div>

        <a href="{{ route('admin.blogs.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl font-bold text-sm transition shadow-lg shadow-blue-500/30 flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Tulis Artikel
        </a>
    </div>
</div>

@if(session('success')) <div class="bg-green-50 text-green-700 p-4 rounded-xl mb-6 font-bold border border-green-200">{{ session('success') }}</div> @endif

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-100">
                <th class="px-6 py-4 font-bold">Artikel</th>
                <th class="px-6 py-4 font-bold">Kategori</th>
                <th class="px-6 py-4 font-bold text-center"><i class="fa-solid fa-eye"></i> Views</th>
                <th class="px-6 py-4 font-bold text-center">Status</th>
                <th class="px-6 py-4 font-bold text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="text-sm">
            @forelse($posts as $post)
            <tr class="border-b border-gray-50 hover:bg-slate-50 transition">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-12 rounded-lg bg-gray-100 overflow-hidden flex-shrink-0 border border-gray-200">
                            <img src="{{ asset('images/' . $post->image_path) }}" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800 line-clamp-1 mb-1">{{ $post->title }}</h4>
                            <p class="text-[10px] text-gray-400 font-medium">{{ $post->created_at->format('d M Y') }} • Oleh {{ strtok($post->author->name, ' ') }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-[10px] font-bold">{{ $post->category->name }}</span>
                </td>
                <td class="px-6 py-4 text-center font-bold text-gray-600">{{ number_format($post->views) }}</td>
                <td class="px-6 py-4 text-center">
                    <button onclick="togglePublish({{ $post->id }}, this)" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors {{ $post->is_published ? 'bg-green-500' : 'bg-gray-300' }}">
                        <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform {{ $post->is_published ? 'translate-x-6' : 'translate-x-1' }}"></span>
                    </button>
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="flex justify-end gap-2">
                        <a href="{{ route('admin.blogs.edit', $post->id) }}" class="w-8 h-8 rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-500 hover:text-white transition flex justify-center items-center"><i class="fa-solid fa-pen text-xs"></i></a>
                        <form action="{{ route('admin.blogs.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Hapus artikel ini?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-500 hover:text-white transition flex justify-center items-center"><i class="fa-solid fa-trash text-xs"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-6 py-10 text-center text-gray-400">Belum ada artikel yang ditulis.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4 border-t border-gray-50">{{ $posts->links('pagination::tailwind') }}</div>
</div>

<script>
function togglePublish(id, btn) {
    fetch(`/admin/blogs/${id}/toggle-status`, {
        method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
    }).then(res => res.json()).then(data => {
        if(data.is_published) {
            btn.classList.remove('bg-gray-300'); btn.classList.add('bg-green-500');
            btn.children[0].classList.remove('translate-x-1'); btn.children[0].classList.add('translate-x-6');
        } else {
            btn.classList.remove('bg-green-500'); btn.classList.add('bg-gray-300');
            btn.children[0].classList.remove('translate-x-6'); btn.children[0].classList.add('translate-x-1');
        }
    });
}
</script>
@endsection