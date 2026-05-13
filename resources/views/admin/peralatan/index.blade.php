@extends('layouts.app')

@section('title', 'Katalog Peralatan')

@section('content')
<div class="w-full space-y-6 pb-10">
    
    <!-- Header Area -->
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-blue-50 text-pln-cyan rounded-2xl flex items-center justify-center text-2xl border-2 border-blue-100 shadow-inner relative group">
                <i class="fa-solid fa-toolbox group-hover:scale-110 transition-transform duration-300"></i>
            </div>
            <div>
                <h1 class="text-2xl font-extrabold text-gray-800 tracking-tight">Katalog Peralatan</h1>
                <p class="text-sm text-gray-500 font-medium mt-0.5">Kelola daftar alat utama, spesifikasi, dan total stok inventaris.</p>
            </div>
        </div>
        <a href="{{ route('admin.peralatan.create') }}" class="w-full md:w-auto px-6 py-3 bg-pln-cyan text-white font-extrabold rounded-xl border-2 border-pln-cyan hover:bg-[#008Cca] hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 shadow-md flex items-center justify-center gap-2">
            <i class="fa-solid fa-circle-plus"></i> Tambah Alat Baru
        </a>
    </div>

    <!-- Area Filter & Pencarian -->
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200">
        <form method="GET" action="{{ route('admin.peralatan.index') }}" class="flex flex-col sm:flex-row gap-4">
            
            <!-- Pencarian Teks -->
            <div class="relative flex-1">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-3.5 text-gray-400"></i>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama alat atau spesifikasi..." 
                    class="w-full pl-11 pr-4 py-3 rounded-xl border-2 border-gray-100 focus:border-pln-cyan focus:ring-0 text-sm font-bold text-gray-700 shadow-sm transition hover:border-gray-200">
            </div>

            <!-- Sort Dropdown -->
            <div class="relative w-full sm:w-56">
                <i class="fa-solid fa-arrow-down-a-z absolute left-4 top-3.5 text-gray-400"></i>
                <select name="sort_direction" class="w-full pl-11 pr-10 py-3 rounded-xl border-2 border-gray-100 focus:border-pln-cyan focus:ring-0 text-sm font-bold text-gray-700 appearance-none bg-white shadow-sm cursor-pointer transition hover:border-gray-200">
                    <option value="asc" {{ $sortDirection == 'asc' ? 'selected' : '' }}>A - Z (Nama Alat)</option>
                    <option value="desc" {{ $sortDirection == 'desc' ? 'selected' : '' }}>Z - A (Nama Alat)</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                    <i class="fa-solid fa-chevron-down text-xs"></i>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-2 w-full sm:w-auto">
                <button type="submit" class="flex-1 sm:flex-none px-8 py-3 bg-pln-dark text-white font-extrabold rounded-xl border-2 border-pln-dark hover:bg-gray-800 transition shadow-md flex items-center justify-center gap-2">
                    <i class="fa-solid fa-filter text-sm"></i> Filter
                </button>
                @if($search || $sortDirection != 'asc')
                    <a href="{{ route('admin.peralatan.index') }}" class="px-4 py-3 bg-gray-50 text-red-500 font-bold rounded-xl border-2 border-gray-200 hover:bg-red-50 hover:border-red-200 hover:text-red-600 transition shadow-sm flex items-center justify-center" title="Reset Filter">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tabel Data Katalog -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-pln-cyan text-white text-xs uppercase font-extrabold tracking-wider border-b-2 border-blue-600">
                    <tr>
                        <th class="px-6 py-4 w-24 text-center">Foto</th>
                        <th class="px-6 py-4">Informasi Alat</th>
                        <th class="px-6 py-4">Lokasi Rak</th>
                        <th class="px-6 py-4 text-center">Total Stok</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($peralatan as $item)
                    <tr class="hover:bg-blue-50/40 transition-colors group">
                        
                        <!-- Foto -->
                        <td class="px-6 py-4">
                            <div class="w-16 h-16 rounded-xl border-2 border-gray-100 shadow-sm overflow-hidden bg-white relative group-hover:border-pln-cyan transition-colors mx-auto">
                                @if($item->foto)
                                    <img src="{{ asset('storage/' . $item->foto) }}" alt="Foto Alat" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full flex flex-col items-center justify-center bg-gray-50 text-gray-300">
                                        <i class="fa-solid fa-image text-xl mb-1"></i>
                                    </div>
                                @endif
                            </div>
                        </td>

                        <!-- Nama & Spesifikasi -->
                        <td class="px-6 py-4">
                            <h3 class="font-extrabold text-gray-800 text-base leading-tight mb-1 group-hover:text-pln-cyan transition-colors">{{ $item->nama_alat }}</h3>
                            <p class="text-xs text-gray-500 font-medium line-clamp-2 max-w-sm">{{ $item->spesifikasi ?? 'Tidak ada spesifikasi khusus tercatat.' }}</p>
                        </td>

                        <!-- Lokasi Rak -->
                        <td class="px-6 py-4">
                            @if($item->rak)
                                <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg text-xs font-bold border border-gray-200 shadow-sm">
                                    <i class="fa-solid fa-layer-group text-pln-cyan"></i> {{ $item->rak->nama_rak }}
                                </div>
                            @else
                                <span class="text-xs text-gray-400 font-bold italic flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i> Belum diatur</span>
                            @endif
                        </td>

                        <!-- Total Stok -->
                        <td class="px-6 py-4 text-center">
                            @php
                                // Penentuan warna badge berdasarkan jumlah stok
                                $stockColor = $item->total_stok > 5 ? 'bg-green-50 text-green-700 border-green-200' : 
                                              ($item->total_stok > 0 ? 'bg-yellow-50 text-yellow-700 border-yellow-200' : 'bg-red-50 text-red-700 border-red-200');
                            @endphp
                            <span class="inline-flex flex-col items-center justify-center min-w-[3rem] px-3 py-1.5 rounded-xl {{ $stockColor }} border shadow-sm">
                                <span class="text-lg font-extrabold leading-none">{{ $item->total_stok }}</span>
                                <span class="text-[9px] uppercase tracking-wider font-bold mt-0.5 opacity-80">Unit</span>
                            </span>
                        </td>

                        <!-- Aksi -->
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2 opacity-80 group-hover:opacity-100 transition-opacity">
                                <!-- Tombol Edit -->
                                <a href="{{ route('admin.peralatan.edit', $item->id) }}" class="w-9 h-9 flex items-center justify-center text-blue-600 bg-blue-50 rounded-xl hover:bg-blue-600 hover:text-white border border-blue-200 transition-all duration-300 shadow-sm" title="Edit Alat">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                
                                <!-- Tombol Hapus -->
                                <button type="button" onclick="if(confirm('Peringatan: Menghapus data ini juga akan menghapus seluruh item inventaris yang terkait dengannya. Yakin ingin melanjutkan?')) document.getElementById('delete-form-{{ $item->id }}').submit();" class="w-9 h-9 flex items-center justify-center text-red-600 bg-red-50 rounded-xl hover:bg-red-600 hover:text-white border border-red-200 transition-all duration-300 shadow-sm" title="Hapus Alat">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                                
                                <!-- Form Hapus Hidden -->
                                <form id="delete-form-{{ $item->id }}" action="{{ route('admin.peralatan.destroy', $item->id) }}" method="POST" class="hidden">
                                    @csrf @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-20 h-20 bg-gray-50 border-2 border-dashed border-gray-200 rounded-full flex items-center justify-center mb-4">
                                    <i class="fa-solid fa-box-open text-3xl text-gray-300"></i>
                                </div>
                                <h3 class="text-lg font-extrabold text-gray-700">Katalog Masih Kosong</h3>
                                <p class="text-sm text-gray-500 font-medium mt-1">Belum ada data peralatan yang ditambahkan atau sesuai dengan filter.</p>
                                <a href="{{ route('admin.peralatan.create') }}" class="mt-4 px-5 py-2 bg-pln-cyan text-white text-sm font-bold rounded-lg hover:bg-[#008Cca] transition shadow-sm">
                                    Tambah Alat Sekarang
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            {{ $peralatan->links() }}
        </div>
    </div>
</div>
@endsection