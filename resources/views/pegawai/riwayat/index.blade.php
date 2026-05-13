@extends('layouts.app')

@section('title', 'Riwayat Peminjaman')

@section('content')

@php
    // Helper function untuk generate URL Sorting
    function sortUrl($field, $currentField, $currentDirection) {
        $newDirection = ($currentField === $field && $currentDirection === 'asc') ? 'desc' : 'asc';
        return request()->fullUrlWithQuery(['sort_field' => $field, 'sort_direction' => $newDirection]);
    }
    // Helper function untuk merender Icon Sorting
    function sortIcon($field, $currentField, $currentDirection) {
        if ($currentField !== $field) return '<i class="fa-solid fa-sort text-blue-200/50 ml-1"></i>';
        return $currentDirection === 'asc' 
            ? '<i class="fa-solid fa-sort-up text-white ml-1"></i>' 
            : '<i class="fa-solid fa-sort-down text-white ml-1"></i>';
    }
@endphp

<div class="w-full space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800"><i class="fa-solid fa-clock-history text-pln-cyan mr-2"></i> Riwayat Peminjaman Saya</h1>
            <p class="text-sm text-gray-500 mt-1">Pantau status permohonan dan daftar alat yang sedang atau pernah Anda pinjam.</p>
        </div>
    </div>

    <!-- Cards Info Interaktif -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <!-- Semua -->
        <a href="{{ request()->fullUrlWithQuery(['status_peminjaman' => '']) }}" class="bg-white p-4 rounded-2xl shadow-sm border-2 border-gray-100 hover:border-gray-300 transition group flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Semua Data</p>
                <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-gray-500 group-hover:bg-gray-100"><i class="fa-solid fa-layer-group"></i></div>
            </div>
            <h3 class="text-2xl font-extrabold text-gray-800 mt-2">{{ $stats['total'] }}</h3>
        </a>
        
        <!-- Menunggu -->
        <a href="{{ request()->fullUrlWithQuery(['status_peminjaman' => 'Menunggu Verifikasi']) }}" class="bg-white p-4 rounded-2xl shadow-sm border-2 border-blue-100 hover:border-blue-400 transition group flex flex-col justify-between relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-16 h-16 bg-blue-50 rounded-full z-0 transition group-hover:scale-150"></div>
            <div class="flex justify-between items-start z-10">
                <p class="text-xs font-bold text-blue-600 uppercase tracking-wider">Menunggu</p>
                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 group-hover:bg-blue-200"><i class="fa-solid fa-hourglass-half"></i></div>
            </div>
            <h3 class="text-2xl font-extrabold text-gray-800 mt-2 z-10">{{ $stats['menunggu'] }}</h3>
        </a>

        <!-- Aktif / Sedang Dipinjam -->
        <a href="{{ request()->fullUrlWithQuery(['status_peminjaman' => 'Sedang Dipinjam']) }}" class="bg-white p-4 rounded-2xl shadow-sm border-2 border-yellow-100 hover:border-yellow-400 transition group flex flex-col justify-between relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-16 h-16 bg-yellow-50 rounded-full z-0 transition group-hover:scale-150"></div>
            <div class="flex justify-between items-start z-10">
                <p class="text-xs font-bold text-yellow-600 uppercase tracking-wider">Aktif (Dibawa)</p>
                <div class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-600 group-hover:bg-yellow-200"><i class="fa-solid fa-person-digging"></i></div>
            </div>
            <h3 class="text-2xl font-extrabold text-gray-800 mt-2 z-10">{{ $stats['aktif'] }}</h3>
        </a>

        <!-- Selesai / Dikembalikan -->
        <a href="{{ request()->fullUrlWithQuery(['status_peminjaman' => 'Dikembalikan']) }}" class="bg-white p-4 rounded-2xl shadow-sm border-2 border-green-100 hover:border-green-400 transition group flex flex-col justify-between relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-16 h-16 bg-green-50 rounded-full z-0 transition group-hover:scale-150"></div>
            <div class="flex justify-between items-start z-10">
                <p class="text-xs font-bold text-green-600 uppercase tracking-wider">Selesai</p>
                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center text-green-600 group-hover:bg-green-200"><i class="fa-solid fa-check-double"></i></div>
            </div>
            <h3 class="text-2xl font-extrabold text-gray-800 mt-2 z-10">{{ $stats['selesai'] }}</h3>
        </a>

        <!-- Ditolak -->
        <a href="{{ request()->fullUrlWithQuery(['status_peminjaman' => 'Ditolak']) }}" class="bg-white p-4 rounded-2xl shadow-sm border-2 border-red-100 hover:border-red-400 transition group flex flex-col justify-between relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-16 h-16 bg-red-50 rounded-full z-0 transition group-hover:scale-150"></div>
            <div class="flex justify-between items-start z-10">
                <p class="text-xs font-bold text-red-600 uppercase tracking-wider">Ditolak</p>
                <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center text-red-600 group-hover:bg-red-200"><i class="fa-solid fa-ban"></i></div>
            </div>
            <h3 class="text-2xl font-extrabold text-gray-800 mt-2 z-10">{{ $stats['ditolak'] }}</h3>
        </a>
    </div>

    <!-- Filter & Pencarian -->
    <div class="bg-white p-5 rounded-2xl shadow-sm border-2 border-gray-200">
        <form method="GET" action="{{ route('pegawai.riwayat.index') }}" class="flex flex-col md:flex-row gap-4">
            
            <!-- Pertahankan nilai sorting yang aktif -->
            <input type="hidden" name="sort_field" value="{{ request('sort_field', 'tanggal_pengajuan') }}">
            <input type="hidden" name="sort_direction" value="{{ request('sort_direction', 'desc') }}">

            <div class="flex-1 relative">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-3.5 text-gray-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode transaksi atau keterangan..." 
                    class="w-full pl-11 pr-4 py-2.5 rounded-xl border-2 border-gray-200 focus:border-pln-cyan focus:ring-0 text-sm font-medium">
            </div>
            <div class="md:w-64 relative">
                <i class="fa-solid fa-filter absolute left-4 top-3.5 text-gray-400"></i>
                <select name="status_peminjaman" class="w-full pl-11 pr-4 py-2.5 rounded-xl border-2 border-gray-200 focus:border-pln-cyan focus:ring-0 text-sm font-bold appearance-none cursor-pointer">
                    <option value="">Semua Status</option>
                    <option value="Menunggu Verifikasi" {{ request('status_peminjaman') == 'Menunggu Verifikasi' ? 'selected' : '' }}>Menunggu</option>
                    <option value="Sedang Dipinjam" {{ request('status_peminjaman') == 'Sedang Dipinjam' ? 'selected' : '' }}>Aktif (Dibawa)</option>
                    <option value="Dikembalikan" {{ request('status_peminjaman') == 'Dikembalikan' ? 'selected' : '' }}>Selesai</option>
                    <option value="Ditolak" {{ request('status_peminjaman') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                    <i class="fa-solid fa-chevron-down text-xs"></i>
                </div>
            </div>
            <button type="submit" class="px-8 py-2.5 bg-pln-dark text-white rounded-xl shadow-md font-bold text-sm border-2 border-pln-dark hover:bg-gray-800 transition flex items-center justify-center gap-2">
                Terapkan
            </button>
            @if(request('search') || request('status_peminjaman'))
                <a href="{{ route('pegawai.riwayat.index') }}" class="px-4 py-2.5 bg-gray-100 text-gray-600 rounded-xl font-bold text-sm border-2 border-gray-200 hover:bg-gray-200 transition flex items-center justify-center" title="Reset Filter">
                    <i class="fa-solid fa-rotate-right"></i>
                </a>
            @endif
        </form>
    </div>

    <!-- Tabel Data -->
    <div class="bg-white rounded-2xl shadow-sm border-2 border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-pln-cyan text-white text-xs uppercase font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-4 cursor-pointer hover:bg-blue-600 transition" onclick="window.location='{{ sortUrl('kode_peminjaman', $sortField, $sortDirection) }}'">
                            <div class="flex items-center gap-1">Kode Transaksi {!! sortIcon('kode_peminjaman', $sortField, $sortDirection) !!}</div>
                        </th>
                        <th class="px-6 py-4 cursor-pointer hover:bg-blue-600 transition" onclick="window.location='{{ sortUrl('tanggal_pengajuan', $sortField, $sortDirection) }}'">
                            <div class="flex items-center gap-1">Tgl Pinjam {!! sortIcon('tanggal_pengajuan', $sortField, $sortDirection) !!}</div>
                        </th>
                        <th class="px-6 py-4">
                            Tujuan Lokasi (Unit)
                        </th>
                        <th class="px-6 py-4 cursor-pointer hover:bg-blue-600 transition" onclick="window.location='{{ sortUrl('estimasi_kembali', $sortField, $sortDirection) }}'">
                            <div class="flex items-center gap-1">Estimasi Kembali {!! sortIcon('estimasi_kembali', $sortField, $sortDirection) !!}</div>
                        </th>
                        <th class="px-6 py-4 text-center cursor-pointer hover:bg-blue-600 transition" onclick="window.location='{{ sortUrl('status_peminjaman', $sortField, $sortDirection) }}'">
                            <div class="flex items-center justify-center gap-1">Status {!! sortIcon('status_peminjaman', $sortField, $sortDirection) !!}</div>
                        </th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-sm">
                    @forelse($riwayat as $row)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-bold text-pln-cyan">{{ $row->kode_peminjaman }}</td>
                        <td class="px-6 py-4 text-gray-600 font-medium">
                            {{ \Carbon\Carbon::parse($row->tanggal_pengajuan)->format('d/m/Y') }}
                            <span class="block text-xs text-gray-400">{{ \Carbon\Carbon::parse($row->tanggal_pengajuan)->format('H:i') }} WIB</span>
                        </td>
                        <td class="px-6 py-4 font-bold text-gray-800">{{ $row->unit_tujuan->nama_unit ?? '-' }}</td>
                        <td class="px-6 py-4 text-red-600 font-bold">
                            {{ \Carbon\Carbon::parse($row->estimasi_kembali)->format('d/m/Y') }}
                            <span class="block text-xs font-normal opacity-70">{{ \Carbon\Carbon::parse($row->estimasi_kembali)->format('H:i') }} WIB</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($row->status_peminjaman == 'Menunggu Verifikasi')
                                <span class="px-3 py-1.5 bg-blue-50 text-blue-600 font-bold rounded-lg border-2 border-blue-100 text-[11px] uppercase tracking-wide flex items-center justify-center gap-1 w-max mx-auto">
                                    <i class="fa-solid fa-clock w-3"></i> Pending
                                </span>
                            @elseif($row->status_peminjaman == 'Sedang Dipinjam')
                                <span class="px-3 py-1.5 bg-yellow-50 text-yellow-700 font-bold rounded-lg border-2 border-yellow-200 text-[11px] uppercase tracking-wide flex items-center justify-center gap-1 w-max mx-auto">
                                    <i class="fa-solid fa-truck-fast w-3"></i> Aktif
                                </span>
                            @elseif($row->status_peminjaman == 'Dikembalikan')
                                <span class="px-3 py-1.5 bg-green-50 text-green-700 font-bold rounded-lg border-2 border-green-200 text-[11px] uppercase tracking-wide flex items-center justify-center gap-1 w-max mx-auto">
                                    <i class="fa-solid fa-check w-3"></i> Selesai
                                </span>
                            @else
                                <span class="px-3 py-1.5 bg-red-50 text-red-600 font-bold rounded-lg border-2 border-red-200 text-[11px] uppercase tracking-wide flex items-center justify-center gap-1 w-max mx-auto">
                                    <i class="fa-solid fa-xmark w-3"></i> Ditolak
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center items-center gap-2">
                                <a href="{{ route('pegawai.riwayat.show', $row->id) }}" title="Lihat Detail" class="w-9 h-9 flex items-center justify-center bg-white text-pln-cyan border-2 border-pln-cyan rounded-lg hover:bg-pln-cyan hover:text-white transition">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                
                                <!-- Tombol Kembalikan Muncul Jika Status "Sedang Dipinjam" -->
                                @if($row->status_peminjaman == 'Sedang Dipinjam')
                                <a href="{{ route('pegawai.pengembalian.form', $row->id) }}" title="Kembalikan Alat" class="w-9 h-9 flex items-center justify-center bg-white text-green-600 border-2 border-green-600 rounded-lg hover:bg-green-600 hover:text-white transition group relative">
                                    <i class="fa-solid fa-rotate-left"></i>
                                    <span class="absolute -top-2 -right-2 flex h-3 w-3">
                                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                      <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                                    </span>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                    <i class="fa-solid fa-folder-open text-3xl text-gray-300"></i>
                                </div>
                                <h3 class="text-lg font-bold text-gray-700">Tidak ada data ditemukan</h3>
                                <p class="text-sm text-gray-500 mt-1">Belum ada riwayat peminjaman atau filter Anda tidak cocok.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination Component -->
        <div class="p-5 border-t border-gray-100 bg-gray-50/50">
            {{ $riwayat->links() }}
        </div>
    </div>
</div>
@endsection