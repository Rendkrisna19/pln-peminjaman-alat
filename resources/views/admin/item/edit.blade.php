@extends('layouts.app')

@section('title', 'Edit Kode Barang Fisik')

@section('content')
<div class="w-full md:max-w-4xl mx-auto space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.item-inventaris.index') }}" class="p-2.5 bg-white rounded-xl shadow-sm border-2 border-gray-200 hover:bg-gray-50 text-gray-600 transition">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Edit Data Item: <span class="text-pln-cyan">{{ $item_inventaris->kode_barcode }}</span></h1>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border-2 border-gray-200 p-6 sm:p-8">
        <form action="{{ route('admin.item-inventaris.update', $item_inventaris->id) }}" method="POST" class="space-y-6">
            @csrf @method('PUT')

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Katalog Induk Alat <span class="text-red-500">*</span></label>
                <select name="peralatan_id" required class="w-full px-4 py-3 rounded-xl border-2 border-gray-300 focus:border-pln-cyan focus:ring-0 transition-colors font-medium">
                    @foreach($peralatan as $p)
                        <option value="{{ $p->id }}" {{ old('peralatan_id', $item_inventaris->peralatan_id) == $p->id ? 'selected' : '' }}>
                            {{ $p->nama_alat }}
                        </option>
                    @endforeach
                </select>
                @error('peralatan_id') <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Kode Barang Fisik <span class="text-red-500">*</span></label>
                <div class="relative">
                    <i class="fa-solid fa-hashtag absolute left-4 top-3.5 text-gray-400"></i>
                    <input type="text" name="kode_barcode" value="{{ old('kode_barcode', $item_inventaris->kode_barcode) }}" required 
                        class="w-full pl-11 pr-4 py-3 rounded-xl border-2 border-gray-300 focus:border-pln-cyan focus:ring-0 transition-colors font-mono font-bold text-pln-cyan bg-gray-50">
                </div>
                @error('kode_barcode') <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Kondisi Fisik Alat <span class="text-red-500">*</span></label>
                    <select name="kondisi" required class="w-full px-4 py-3 rounded-xl border-2 border-gray-300 focus:border-pln-cyan focus:ring-0 transition-colors font-medium">
                        <option value="Baik" {{ old('kondisi', $item_inventaris->kondisi) == 'Baik' ? 'selected' : '' }}>Baik / Normal</option>
                        <option value="Rusak Ringan" {{ old('kondisi', $item_inventaris->kondisi) == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                        <option value="Rusak Berat" {{ old('kondisi', $item_inventaris->kondisi) == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Status Operasional <span class="text-red-500">*</span></label>
                    <select name="status_ketersediaan" required class="w-full px-4 py-3 rounded-xl border-2 border-gray-300 focus:border-pln-cyan focus:ring-0 transition-colors font-medium">
                        <option value="Tersedia" {{ old('status_ketersediaan', $item_inventaris->status_ketersediaan) == 'Tersedia' ? 'selected' : '' }}>Tersedia di Gudang</option>
                        <option value="Dipinjam" {{ old('status_ketersediaan', $item_inventaris->status_ketersediaan) == 'Dipinjam' ? 'selected' : '' }}>Sedang Dipinjam</option>
                        <option value="Diperbaiki" {{ old('status_ketersediaan', $item_inventaris->status_ketersediaan) == 'Diperbaiki' ? 'selected' : '' }}>Sedang Diperbaiki (Maintenance)</option>
                    </select>
                </div>
            </div>

            <hr class="border-2 border-gray-100">

            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.item-inventaris.index') }}" class="px-6 py-3 bg-white text-gray-700 font-bold rounded-xl border-2 border-gray-300 hover:bg-gray-50 transition">Batal</a>
                <button type="submit" class="px-6 py-3 bg-pln-yellow text-gray-900 font-bold rounded-xl shadow-md hover:bg-[#e5c100] transition border-2 border-[#e5c100] flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i> Update Data Fisik
                </button>
            </div>
        </form>
    </div>
</div>
@endsection