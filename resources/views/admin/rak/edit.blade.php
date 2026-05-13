@extends('layouts.app')

@section('title', 'Edit Rak Penyimpanan')

@section('content')
<div class="w-full md:max-w-3xl mx-auto space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.rak-penyimpanan.index') }}" class="p-2.5 bg-white rounded-xl shadow-sm border-2 border-gray-200 hover:bg-gray-50 text-gray-600 transition">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Edit Rak: <span class="text-pln-cyan">{{ $rak_penyimpanan->nama_rak }}</span></h1>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border-2 border-gray-200 p-6 sm:p-8">
        <form action="{{ route('admin.rak-penyimpanan.update', $rak_penyimpanan->id) }}" method="POST" class="space-y-6">
            @csrf @method('PUT')

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Nama Rak <span class="text-red-500">*</span></label>
                <div class="relative">
                    <i class="fa-solid fa-server absolute left-4 top-3.5 text-gray-400"></i>
                    <input type="text" name="nama_rak" value="{{ old('nama_rak', $rak_penyimpanan->nama_rak) }}" required 
                        class="w-full pl-11 pr-4 py-3 rounded-xl border-2 border-gray-300 focus:border-pln-cyan focus:ring-0 transition-colors font-bold text-gray-800">
                </div>
                @error('nama_rak') <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Detail Lokasi (Opsional)</label>
                <div class="relative">
                    <i class="fa-solid fa-location-dot absolute left-4 top-3.5 text-gray-400"></i>
                    <input type="text" name="lokasi_rak" value="{{ old('lokasi_rak', $rak_penyimpanan->lokasi_rak) }}" 
                        class="w-full pl-11 pr-4 py-3 rounded-xl border-2 border-gray-300 focus:border-pln-cyan focus:ring-0 transition-colors font-medium">
                </div>
                @error('lokasi_rak') <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>

            <hr class="border-2 border-gray-100">

            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.rak-penyimpanan.index') }}" class="px-6 py-3 bg-white text-gray-700 font-bold rounded-xl border-2 border-gray-300 hover:bg-gray-50 transition">Batal</a>
                <button type="submit" class="px-6 py-3 bg-pln-yellow text-gray-900 font-bold rounded-xl shadow-md hover:bg-[#e5c100] transition border-2 border-[#e5c100] flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i> Update Data Rak
                </button>
            </div>
        </form>
    </div>
</div>
@endsection