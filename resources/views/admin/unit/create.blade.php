@extends('layouts.app')

@section('title', 'Tambah Unit Operasional')

@section('content')
<div class="w-full md:max-w-3xl mx-auto space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.unit-lokasi.index') }}" class="p-2.5 bg-white rounded-xl shadow-sm border-2 border-gray-200 hover:bg-gray-50 text-gray-600 transition">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Tambah Unit Baru</h1>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border-2 border-gray-200 p-6 sm:p-8">
        <form action="{{ route('admin.unit-lokasi.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Nama Unit Operasional <span class="text-red-500">*</span></label>
                <div class="relative">
                    <i class="fa-solid fa-building absolute left-4 top-3.5 text-gray-400"></i>
                    <input type="text" name="nama_unit" value="{{ old('nama_unit') }}" required placeholder="Contoh: PLTA ASAHAN 3" 
                        class="w-full pl-11 pr-4 py-3 rounded-xl border-2 border-gray-300 focus:border-pln-cyan focus:ring-0 transition-colors font-bold text-gray-800">
                </div>
                @error('nama_unit') <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Jenis Unit (Opsional)</label>
                <div class="relative">
                    <i class="fa-solid fa-layer-group absolute left-4 top-3.5 text-gray-400"></i>
                    <input type="text" name="jenis_unit" value="{{ old('jenis_unit') }}" placeholder="Contoh: PLTA, PLTMH, Gardu Induk" 
                        class="w-full pl-11 pr-4 py-3 rounded-xl border-2 border-gray-300 focus:border-pln-cyan focus:ring-0 transition-colors font-medium">
                </div>
                @error('jenis_unit') <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>

            <hr class="border-2 border-gray-100">

            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.unit-lokasi.index') }}" class="px-6 py-3 bg-white text-gray-700 font-bold rounded-xl border-2 border-gray-300 hover:bg-gray-50 transition">Batal</a>
                <button type="submit" class="px-6 py-3 bg-pln-cyan text-white font-bold rounded-xl shadow-md hover:bg-[#008Cca] transition border-2 border-[#008Cca] flex items-center gap-2">
                    <i class="fa-solid fa-save"></i> Simpan Unit
                </button>
            </div>
        </form>
    </div>
</div>
@endsection