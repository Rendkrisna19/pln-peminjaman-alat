@extends('layouts.app')

@section('title', 'Verifikasi Peminjaman')

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

    // Default values jika variabel belum diset di controller (mencegah error)
    $sortField = request('sort_field', 'tanggal_pengajuan');
    $sortDirection = request('sort_direction', 'desc');
@endphp

<div class="w-full space-y-6 pb-10">
    
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-blue-50 text-pln-cyan rounded-2xl flex items-center justify-center text-2xl border-2 border-blue-100 shadow-inner relative">
                <i class="fa-solid fa-clipboard-check"></i>
                <!-- Indikator ada antrean (Opsional: Jika ada data yg menunggu) -->
                @if($peminjaman->contains('status_peminjaman', 'Menunggu Verifikasi'))
                <span class="absolute -top-1 -right-1 flex h-4 w-4">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-4 w-4 bg-red-500 border-2 border-white"></span>
                </span>
                @endif
            </div>
            <div>
                <h1 class="text-2xl font-extrabold text-gray-800 tracking-tight">Verifikasi Peminjaman</h1>
                <p class="text-sm text-gray-500 font-medium mt-0.5">Kelola, tinjau, dan proses permohonan peminjaman alat.</p>
            </div>
        </div>
    </div>

    <!-- Filter & Pencarian -->
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200">
        <form method="GET" action="{{ route('admin.peminjaman.index') }}" class="flex flex-col md:flex-row gap-4">
            
            <!-- Pertahankan parameter sorting -->
            <input type="hidden" name="sort_field" value="{{ $sortField }}">
            <input type="hidden" name="sort_direction" value="{{ $sortDirection }}">

            <!-- Pencarian -->
            <div class="relative flex-1">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-3.5 text-gray-400"></i>
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari Kode Peminjaman / Nama Peminjam..." 
                    class="w-full pl-11 pr-4 py-3 rounded-xl border-2 border-gray-100 focus:border-pln-cyan focus:ring-0 text-sm font-bold text-gray-700 shadow-sm transition hover:border-gray-200">
            </div>

            <!-- Filter Status -->
            <div class="relative md:w-64">
                <i class="fa-solid fa-filter absolute left-4 top-3.5 text-gray-400"></i>
                <select name="status_peminjaman" class="w-full pl-11 pr-10 py-3 rounded-xl border-2 border-gray-100 focus:border-pln-cyan focus:ring-0 text-sm font-bold text-gray-700 appearance-none bg-white shadow-sm cursor-pointer transition hover:border-gray-200">
                    <option value="">Semua Status</option>
                    <option value="Menunggu Verifikasi" {{ ($filterStatus ?? '') == 'Menunggu Verifikasi' ? 'selected' : '' }}>🟡 Menunggu Verifikasi</option>
                    <option value="Sedang Dipinjam" {{ ($filterStatus ?? '') == 'Sedang Dipinjam' ? 'selected' : '' }}>🔵 Sedang Dipinjam</option>
                    <option value="Dikembalikan" {{ ($filterStatus ?? '') == 'Dikembalikan' ? 'selected' : '' }}>🟢 Dikembalikan</option>
                    <option value="Ditolak" {{ ($filterStatus ?? '') == 'Ditolak' ? 'selected' : '' }}>🔴 Ditolak</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                    <i class="fa-solid fa-chevron-down text-xs"></i>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-2">
                <button type="submit" class="px-8 py-3 bg-pln-dark text-white font-extrabold rounded-xl border-2 border-pln-dark hover:bg-gray-800 transition shadow-md flex items-center justify-center gap-2">
                    Cari
                </button>
                @if(($search ?? '') || ($filterStatus ?? ''))
                    <a href="{{ route('admin.peminjaman.index') }}" class="px-4 py-3 bg-gray-50 text-red-500 font-bold rounded-xl border-2 border-gray-200 hover:bg-red-50 hover:border-red-200 hover:text-red-600 transition shadow-sm flex items-center justify-center" title="Hapus Filter">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tabel Data -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-pln-cyan text-white text-xs uppercase font-extrabold tracking-wider border-b-2 border-blue-600">
                    <tr>
                        <th class="px-6 py-4 cursor-pointer hover:bg-[#008Cca] transition" onclick="window.location='{{ sortUrl('kode_peminjaman', $sortField, $sortDirection) }}'">
                            <div class="flex items-center gap-1">Kode Transaksi {!! sortIcon('kode_peminjaman', $sortField, $sortDirection) !!}</div>
                        </th>
                        <th class="px-6 py-4">Peminjam</th>
                        <th class="px-6 py-4">Unit Tujuan</th>
                        <th class="px-6 py-4 cursor-pointer hover:bg-[#008Cca] transition" onclick="window.location='{{ sortUrl('tanggal_pengajuan', $sortField, $sortDirection) }}'">
                            <div class="flex items-center gap-1">Tgl Pengajuan {!! sortIcon('tanggal_pengajuan', $sortField, $sortDirection) !!}</div>
                        </th>
                        <th class="px-6 py-4 cursor-pointer hover:bg-[#008Cca] transition" onclick="window.location='{{ sortUrl('status_peminjaman', $sortField, $sortDirection) }}'">
                            <div class="flex items-center gap-1">Status {!! sortIcon('status_peminjaman', $sortField, $sortDirection) !!}</div>
                        </th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($peminjaman as $row)
                    <tr class="hover:bg-blue-50/30 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <!-- Red Dot Notif Khusus Status Menunggu Verifikasi -->
                                @if($row->status_peminjaman == 'Menunggu Verifikasi')
                                    <span class="relative flex h-2.5 w-2.5" title="Butuh Tindakan Cepat!">
                                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                      <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500"></span>
                                    </span>
                                @endif
                                <span class="font-bold text-pln-cyan text-sm">{{ $row->kode_peminjaman }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 font-bold text-sm text-gray-800">{{ $row->user->name ?? $row->user->nama_lengkap ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600 font-medium">{{ $row->unit_tujuan->nama_unit ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-bold text-gray-800">{{ \Carbon\Carbon::parse($row->tanggal_pengajuan)->format('d M Y') }}</span>
                            <span class="block text-xs text-gray-400 font-medium">{{ \Carbon\Carbon::parse($row->tanggal_pengajuan)->format('H:i') }} WIB</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($row->status_peminjaman == 'Menunggu Verifikasi')
                                <span class="px-3 py-1.5 bg-yellow-50 text-yellow-700 border border-yellow-200 rounded-lg text-[11px] font-extrabold uppercase tracking-wider flex items-center gap-1.5 w-max shadow-sm">
                                    <i class="fa-solid fa-hourglass-half"></i> Pending
                                </span>
                            @elseif($row->status_peminjaman == 'Sedang Dipinjam')
                                <span class="px-3 py-1.5 bg-blue-50 text-blue-700 border border-blue-200 rounded-lg text-[11px] font-extrabold uppercase tracking-wider flex items-center gap-1.5 w-max shadow-sm">
                                    <i class="fa-solid fa-person-digging"></i> Aktif
                                </span>
                            @elseif($row->status_peminjaman == 'Dikembalikan')
                                <span class="px-3 py-1.5 bg-green-50 text-green-700 border border-green-200 rounded-lg text-[11px] font-extrabold uppercase tracking-wider flex items-center gap-1.5 w-max shadow-sm">
                                    <i class="fa-solid fa-check-double"></i> Selesai
                                </span>
                            @else
                                <span class="px-3 py-1.5 bg-red-50 text-red-700 border border-red-200 rounded-lg text-[11px] font-extrabold uppercase tracking-wider flex items-center gap-1.5 w-max shadow-sm">
                                    <i class="fa-solid fa-ban"></i> Ditolak
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($row->status_peminjaman == 'Menunggu Verifikasi')
                                <!-- Tombol Proses (Lebih mencolok untuk yang pending) -->
                                <a href="{{ route('admin.peminjaman.show', $row->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-pln-cyan text-white rounded-xl text-xs font-bold shadow-md hover:bg-[#008Cca] hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 relative">
                                    <i class="fa-solid fa-clipboard-list"></i> Proses
                                    <span class="absolute -top-1 -right-1 flex h-3 w-3">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                                    </span>
                                </a>
                            @else
                                <!-- Tombol Detail Biasa (Untuk yang sudah diproses) -->
                                <a href="{{ route('admin.peminjaman.show', $row->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 border border-gray-200 rounded-xl text-xs font-bold hover:bg-gray-200 transition-colors shadow-sm">
                                    <i class="fa-solid fa-eye"></i> Detail
                                </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-20 h-20 bg-gray-50 border-2 border-dashed border-gray-200 rounded-full flex items-center justify-center mb-4">
                                    <i class="fa-solid fa-folder-open text-3xl text-gray-300"></i>
                                </div>
                                <h3 class="text-lg font-bold text-gray-700">Tidak ada data ditemukan</h3>
                                <p class="text-sm text-gray-500 mt-1">Belum ada peminjaman yang sesuai dengan filter Anda.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination Component -->
        <div class="p-5 border-t border-gray-100 bg-gray-50/50">
            {{ $peminjaman->links() }}
        </div>
    </div>
</div>
@endsection