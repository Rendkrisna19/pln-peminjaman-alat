@extends('layouts.app')

@section('title', 'Tambah Kode Barang Fisik')

@section('content')
<div class="w-full md:max-w-4xl mx-auto space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.item-inventaris.index') }}" class="p-2.5 bg-white rounded-xl shadow-sm border-2 border-gray-200 hover:bg-gray-50 text-gray-600 transition">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Tambah Data Item (Kode Barang)</h1>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border-2 border-gray-200 p-6 sm:p-8">
        <form action="{{ route('admin.item-inventaris.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Katalog Induk Alat <span class="text-red-500">*</span></label>
                <select name="peralatan_id" required class="w-full px-4 py-3 rounded-xl border-2 border-gray-300 focus:border-pln-cyan focus:ring-0 transition-colors font-medium">
                    <option value="">-- Pilih Katalog Alat --</option>
                    @foreach($peralatan as $p)
                        <option value="{{ $p->id }}" {{ old('peralatan_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->nama_alat }} (Tersisa: {{ $p->total_stok }} stok terdaftar)
                        </option>
                    @endforeach
                </select>
                @error('peralatan_id') <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Kode Barang Fisik <span class="text-red-500">*</span></label>
                <div class="relative">
                    <i class="fa-solid fa-hashtag absolute left-4 top-3.5 text-gray-400"></i>
                    <input type="text" name="kode_barcode" value="{{ old('kode_barcode') }}" required placeholder="Contoh: PAND-001-001" 
                        class="w-full pl-11 pr-4 py-3 rounded-xl border-2 border-gray-300 focus:border-pln-cyan focus:ring-0 transition-colors font-mono font-bold text-pln-cyan">
                </div>
                <p class="text-xs text-gray-500 mt-1 font-medium">Pastikan kode tercetak jelas pada fisik alat. Harus unik.</p>
                @error('kode_barcode') <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Kondisi Saat Ini <span class="text-red-500">*</span></label>
                    <select name="kondisi" required class="w-full px-4 py-3 rounded-xl border-2 border-gray-300 focus:border-pln-cyan focus:ring-0 transition-colors font-medium">
                        <option value="Baik" {{ old('kondisi') == 'Baik' ? 'selected' : '' }}>Baik / Normal</option>
                        <option value="Rusak Ringan" {{ old('kondisi') == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                        <option value="Rusak Berat" {{ old('kondisi') == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Status Ketersediaan <span class="text-red-500">*</span></label>
                    <select name="status_ketersediaan" required class="w-full px-4 py-3 rounded-xl border-2 border-gray-300 focus:border-pln-cyan focus:ring-0 transition-colors font-medium">
                        <option value="Tersedia" {{ old('status_ketersediaan') == 'Tersedia' ? 'selected' : '' }}>Tersedia di Gudang</option>
                        <option value="Dipinjam" {{ old('status_ketersediaan') == 'Dipinjam' ? 'selected' : '' }}>Sedang Dipinjam</option>
                        <option value="Diperbaiki" {{ old('status_ketersediaan') == 'Diperbaiki' ? 'selected' : '' }}>Sedang Diperbaiki (Maintenance)</option>
                    </select>
                </div>
            </div>

            <hr class="border-2 border-gray-100">

            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.item-inventaris.index') }}" class="px-6 py-3 bg-white text-gray-700 font-bold rounded-xl border-2 border-gray-300 hover:bg-gray-50 transition">Batal</a>
                <button type="submit" class="px-6 py-3 bg-pln-cyan text-white font-bold rounded-xl shadow-md hover:bg-[#008Cca] transition border-2 border-[#008Cca] flex items-center gap-2">
                    <i class="fa-solid fa-save"></i> Simpan Data Fisik
                </button>
            </div>
        </form>
    </div>
</div>
@endsection