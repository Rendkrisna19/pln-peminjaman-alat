@extends('layouts.app')

@section('title', 'Detail Tiket Peminjaman')

@section('content')

@php
    // Menentukan warna badge berdasarkan status
    $statusColor = match($peminjaman->status_peminjaman) {
        'Menunggu Verifikasi' => 'bg-blue-50 text-blue-600 border-blue-200',
        'Sedang Dipinjam' => 'bg-yellow-50 text-yellow-600 border-yellow-200',
        'Dikembalikan' => 'bg-green-50 text-green-600 border-green-200',
        'Ditolak' => 'bg-red-50 text-red-600 border-red-200',
        default => 'bg-gray-50 text-gray-600 border-gray-200',
    };

    $statusIcon = match($peminjaman->status_peminjaman) {
        'Menunggu Verifikasi' => 'fa-clock',
        'Sedang Dipinjam' => 'fa-person-digging',
        'Dikembalikan' => 'fa-check-double',
        'Ditolak' => 'fa-ban',
        default => 'fa-circle-info',
    };
@endphp

<div class="w-full space-y-6 pb-10">
    
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
        <div class="flex items-center gap-4">
            <a href="{{ url()->previous() }}" class="w-10 h-10 flex items-center justify-center bg-gray-50 border-2 border-gray-200 rounded-xl hover:bg-gray-200 hover:border-gray-300 transition shadow-sm text-gray-600">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-extrabold text-gray-800">Detail Transaksi</h1>
                <p class="text-sm text-gray-500 font-medium">Tiket Ref: <span class="text-pln-cyan font-bold font-mono px-2 py-0.5 bg-blue-50 rounded border border-blue-100">#{{ $peminjaman->kode_peminjaman }}</span></p>
            </div>
        </div>
        
        <div class="px-4 py-2 {{ $statusColor }} border-2 rounded-xl flex items-center gap-2 font-bold uppercase tracking-wider text-sm shadow-sm">
            <i class="fa-solid {{ $statusIcon }}"></i>
            {{ $peminjaman->status_peminjaman }}
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        
        <!-- Kolom Kiri: Info Peminjaman -->
        <div class="xl:col-span-1 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border-2 border-gray-100 overflow-hidden">
                <div class="bg-gray-50 border-b-2 border-gray-100 p-5 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-pln-cyan text-white flex items-center justify-center shadow-sm">
                        <i class="fa-solid fa-file-lines"></i>
                    </div>
                    <h3 class="font-extrabold text-gray-800 text-lg">Informasi Tiket</h3>
                </div>
                
                <div class="p-6 space-y-5">
                    <!-- Timeline Tanggal -->
                    <div class="relative border-l-2 border-gray-100 pl-4 space-y-4 ml-2">
                        <div class="relative">
                            <div class="absolute -left-[23px] top-1 w-3 h-3 bg-blue-400 rounded-full ring-4 ring-white"></div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Tanggal Pengajuan</p>
                            <p class="font-bold text-gray-800">{{ \Carbon\Carbon::parse($peminjaman->tanggal_pengajuan)->translatedFormat('d F Y, H:i') }}</p>
                        </div>
                        <div class="relative">
                            <div class="absolute -left-[23px] top-1 w-3 h-3 bg-yellow-400 rounded-full ring-4 ring-white"></div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Estimasi Pengembalian</p>
                            <p class="font-bold text-gray-800">{{ \Carbon\Carbon::parse($peminjaman->estimasi_kembali)->translatedFormat('d F Y, H:i') }}</p>
                        </div>
                        @if($peminjaman->tanggal_dikembalikan)
                        <div class="relative">
                            <div class="absolute -left-[23px] top-1 w-3 h-3 bg-green-500 rounded-full ring-4 ring-white"></div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Tgl Dikembalikan Aktual</p>
                            <p class="font-bold text-green-600">{{ \Carbon\Carbon::parse($peminjaman->tanggal_dikembalikan)->translatedFormat('d F Y, H:i') }}</p>
                        </div>
                        @endif
                    </div>

                    <hr class="border-gray-100 border-dashed">

                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Lokasi Tujuan / PLTA</p>
                        <p class="font-bold text-gray-800 flex items-center gap-2">
                            <i class="fa-solid fa-location-dot text-red-500"></i> {{ $peminjaman->unit_tujuan->nama_unit ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Keterangan / Urgensi Pekerjaan</p>
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 text-sm font-medium text-gray-700 leading-relaxed shadow-inner">
                            {{ $peminjaman->keterangan_pekerjaan }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Daftar Alat & Bukti -->
        <div class="xl:col-span-2 space-y-6">
            
            <!-- Daftar Alat -->
            <div class="bg-white rounded-2xl shadow-sm border-2 border-gray-100 overflow-hidden">
                <div class="bg-gray-50 border-b-2 border-gray-100 p-5 flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-pln-yellow text-yellow-800 flex items-center justify-center shadow-sm">
                            <i class="fa-solid fa-toolbox"></i>
                        </div>
                        <h3 class="font-extrabold text-gray-800 text-lg">Rincian Alat Dipinjam</h3>
                    </div>
                    <span class="text-xs font-bold text-gray-500 bg-gray-200 px-3 py-1 rounded-full">{{ $peminjaman->detail_peminjaman->count() }} Item</span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-white text-gray-400 text-xs uppercase font-extrabold tracking-wider border-b-2 border-gray-100">
                            <tr>
                                <th class="px-6 py-4">Informasi Alat & Barcode</th>
                                <th class="px-6 py-4">Kondisi Keluar</th>
                                @if($peminjaman->status_peminjaman == 'Dikembalikan')
                                <th class="px-6 py-4">Kondisi Kembali</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($peminjaman->detail_peminjaman as $detail)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="font-extrabold text-gray-800 text-sm mb-1">{{ $detail->item_inventaris->peralatan->nama_alat }}</p>
                                    <div class="flex items-center gap-2">
                                        <i class="fa-solid fa-barcode text-gray-400 text-xs"></i>
                                        <span class="font-mono font-bold text-pln-cyan text-xs bg-blue-50 px-2 py-0.5 rounded border border-blue-100">{{ $detail->item_inventaris->kode_barcode }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 bg-gray-50 text-gray-600 font-bold rounded-lg border border-gray-200 text-xs flex items-center gap-1 w-max">
                                        <div class="w-1.5 h-1.5 rounded-full {{ $detail->kondisi_saat_dipinjam == 'Baik' ? 'bg-green-500' : 'bg-yellow-500' }}"></div>
                                        {{ $detail->kondisi_saat_dipinjam }}
                                    </span>
                                </td>
                                @if($peminjaman->status_peminjaman == 'Dikembalikan')
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 {{ $detail->kondisi_saat_kembali == 'Baik' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-red-50 text-red-700 border-red-200' }} font-bold rounded-lg border text-xs flex items-center gap-1 w-max mb-1">
                                        <div class="w-1.5 h-1.5 rounded-full {{ $detail->kondisi_saat_kembali == 'Baik' ? 'bg-green-500' : 'bg-red-500' }}"></div>
                                        {{ $detail->kondisi_saat_kembali ?? 'Tidak tercatat' }}
                                    </span>
                                    @if($detail->catatan_kerusakan)
                                        <p class="text-[10px] text-red-500 italic font-medium mt-1">"{{ $detail->catatan_kerusakan }}"</p>
                                    @endif
                                </td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if(in_array($peminjaman->status_peminjaman, ['Menunggu Verifikasi']))
                <div class="p-5 bg-blue-50/50 border-t-2 border-blue-100 text-blue-700 flex items-start gap-3">
                    <i class="fa-solid fa-circle-info text-xl mt-0.5"></i>
                    <p class="text-sm font-medium leading-relaxed">
                        Jika status permohonan Anda disetujui, silakan temui Admin di Gudang untuk pengambilan fisik alat sesuai nomor barcode di atas.
                    </p>
                </div>
                @endif
            </div>

            <!-- Bagian Bukti Pengembalian (Hanya muncul jika sudah dikembalikan) -->
            @if($peminjaman->status_peminjaman == 'Dikembalikan')
            <div class="bg-white rounded-2xl shadow-sm border-2 border-gray-100 overflow-hidden">
                <div class="bg-green-50 border-b-2 border-green-100 p-5 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center shadow-sm">
                        <i class="fa-solid fa-camera-retro"></i>
                    </div>
                    <h3 class="font-extrabold text-green-800 text-lg">Bukti Pengembalian Fisik</h3>
                </div>
                
                <div class="p-6 flex flex-col md:flex-row gap-6 items-start">
                    <!-- Foto Bukti (PERBAIKAN: foto_pengembalian) -->
                    <div class="w-full md:w-1/2">
                        @if($peminjaman->foto_pengembalian)
                            <a href="{{ asset('storage/' . $peminjaman->foto_pengembalian) }}" target="_blank" class="block group relative rounded-xl overflow-hidden border-4 border-gray-100 shadow-sm">
                                <img src="{{ asset('storage/' . $peminjaman->foto_pengembalian) }}" alt="Bukti Pengembalian" class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-500">
                                <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <span class="bg-white/90 text-gray-800 px-4 py-2 rounded-lg font-bold text-sm shadow-lg flex items-center gap-2">
                                        <i class="fa-solid fa-magnifying-glass-plus"></i> Lihat Penuh
                                    </span>
                                </div>
                            </a>
                        @else
                            <div class="w-full h-64 bg-gray-50 border-2 border-dashed border-gray-200 rounded-xl flex flex-col items-center justify-center text-gray-400">
                                <i class="fa-solid fa-image-slash text-4xl mb-3"></i>
                                <p class="font-bold text-sm">Tidak ada foto bukti</p>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Catatan Pengembalian Admin -->
                    <div class="w-full md:w-1/2 space-y-4">
                        <div class="bg-gray-50 p-5 rounded-xl border border-gray-200 shadow-inner">
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Verifikasi Admin Gudang</h4>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-xs text-gray-500 mb-0.5">Diverifikasi Oleh:</p>
                                    <p class="font-bold text-gray-800 flex items-center gap-2">
                                        <i class="fa-solid fa-user-shield text-pln-cyan"></i> 
                                        {{ $peminjaman->admin->nama_lengkap ?? 'Admin Sistem' }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-0.5">Catatan Akhir:</p>
                                    <p class="text-sm font-medium text-gray-700 italic">
                                        {{ $peminjaman->catatan_admin ?? 'Tidak ada catatan tambahan dari admin.' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection