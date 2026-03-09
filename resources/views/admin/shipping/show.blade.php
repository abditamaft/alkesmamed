@extends('admin.layouts.app')

@section('content')
<div class="mb-8">
    <a href="{{ route('admin.shipping.index') }}" class="text-sm font-bold text-gray-500 hover:text-blue-600 transition flex items-center gap-2 mb-4">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Provinsi
    </a>
    <h1 class="text-2xl font-black text-gray-800">Ongkos Kirim: {{ $province->name }}</h1>
</div>

@if(session('success')) <div class="bg-green-50 text-green-700 p-4 rounded-xl mb-4 font-bold border border-green-200">{{ session('success') }}</div> @endif
@if($errors->any()) <div class="bg-red-50 text-red-700 p-4 rounded-xl mb-4 font-bold border border-red-200">{{ $errors->first() }}</div> @endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <div class="lg:col-span-1">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 sticky top-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4 border-b border-gray-100 pb-3">Tambah Kota / Kab</h2>
            <form action="{{ route('admin.shipping.storeCity', $province->id) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">Tipe</label>
                    <select name="type" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 outline-none font-medium">
                        <option value="Kota">Kota</option>
                        <option value="Kabupaten">Kabupaten</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">Nama Daerah</label>
                    <input type="text" name="name" required placeholder="Contoh: Sidoarjo" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">Ongkos Kirim (Rp)</label>
                    <input type="number" name="cost" required value="0" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 outline-none font-bold text-blue-600">
                </div>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-xl transition shadow-md mt-2">
                    <i class="fa-solid fa-plus mr-1"></i> Tambah Kota
                </button>
            </form>
        </div>
    </div>

    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                    <th class="px-6 py-4 font-bold">Nama Kota/Kabupaten</th>
                    <th class="px-6 py-4 font-bold">Tarif Ongkir</th>
                    <th class="px-6 py-4 font-bold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @forelse($province->cities as $city)
                <tr class="border-b border-gray-50 hover:bg-slate-50 transition" x-data="{ editMode: false }">
                    <td class="px-6 py-4">
                        <div x-show="!editMode">
                            <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block mb-0.5">{{ $city->type }}</span>
                            <span class="font-bold text-gray-800 text-base">{{ $city->name }}</span>
                        </div>
                        <form x-show="editMode" id="form-edit-{{ $city->id }}" action="{{ route('admin.shipping.updateCity', $city->id) }}" method="POST" class="flex gap-2" style="display: none;">
                            @csrf @method('PUT')
                            <select name="type" class="border rounded px-2 py-1 text-xs"><option value="Kota" {{ $city->type=='Kota'?'selected':'' }}>Kota</option><option value="Kabupaten" {{ $city->type=='Kabupaten'?'selected':'' }}>Kab.</option></select>
                            <input type="text" name="name" value="{{ $city->name }}" required class="border rounded px-2 py-1 text-xs w-full">
                        </form>
                    </td>
                    <td class="px-6 py-4">
                        <div x-show="!editMode" class="font-black text-blue-600 text-base">
                            Rp {{ number_format($city->shippingRate->cost ?? 0, 0, ',', '.') }}
                        </div>
                        <input x-show="editMode" form="form-edit-{{ $city->id }}" type="number" name="cost" value="{{ $city->shippingRate->cost ?? 0 }}" required class="border rounded px-2 py-1 text-xs font-bold text-blue-600 w-32" style="display: none;">
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2" x-show="!editMode">
                            <button @click="editMode = true" class="w-8 h-8 rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-500 hover:text-white transition flex justify-center items-center"><i class="fa-solid fa-pen text-xs"></i></button>
                            <form action="{{ route('admin.shipping.destroyCity', $city->id) }}" method="POST" onsubmit="return confirm('Hapus kota ini?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-500 hover:text-white transition flex justify-center items-center"><i class="fa-solid fa-trash text-xs"></i></button>
                            </form>
                        </div>
                        <div class="flex justify-end gap-2" x-show="editMode" style="display: none;">
                            <button type="submit" form="form-edit-{{ $city->id }}" class="bg-green-500 text-white px-3 py-1 rounded text-xs font-bold shadow">Simpan</button>
                            <button type="button" @click="editMode = false" class="bg-gray-300 text-gray-700 px-3 py-1 rounded text-xs font-bold">Batal</button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="3" class="px-6 py-10 text-center text-gray-400 font-medium">Belum ada kota yang ditambahkan ke provinsi ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection