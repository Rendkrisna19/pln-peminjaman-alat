@extends('layouts.app')

@section('title', 'Dashboard Pegawai')

@section('content')
<div class="w-full space-y-6 pb-10">
    
    <div class="relative w-full rounded-3xl overflow-hidden shadow-sm border border-gray-100 group min-h-[320px] md:min-h-[400px] flex items-center bg-pln-dark">
        
        <img src="{{ asset('images/banner1.png') }}" alt="Banner PLN" class="absolute inset-0 w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-1000 ease-in-out opacity-90">
        
        <div class="absolute inset-0 bg-gradient-to-r from-gray-900/95 via-gray-900/70 to-transparent"></div>
        
        <div class="relative z-10 p-6 sm:p-10 w-full md:w-3/4 lg:w-2/3">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-white/10 backdrop-blur-md rounded-full border border-white/20 w-max mb-4">
                <span class="w-2.5 h-2.5 rounded-full bg-green-400 animate-pulse"></span>
                <span class="text-[10px] sm:text-xs font-bold text-white tracking-widest uppercase">Portal Pegawai Aktif</span>
            </div>

            <h1 class="text-3xl sm:text-4xl md:text-5xl font-black text-white mb-3 drop-shadow-lg leading-tight">
                Selamat Datang, <br class="hidden sm:block">
                <span class="text-pln-cyan">{{ Auth::user()->nama_lengkap ?? Auth::user()->name }}</span>!
            </h1>
            
            <p class="text-gray-300 font-medium text-sm sm:text-base leading-relaxed mb-8 drop-shadow max-w-2xl">
                Sistem Informasi Peminjaman & Monitoring Peralatan. Pastikan untuk selalu mengecek kondisi fisik alat sebelum dipinjam dan sesudah bekerja di lapangan.
            </p>
            
            <div>
                <a href="{{ route('pegawai.katalog.index') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-pln-yellow text-gray-900 font-extrabold rounded-xl shadow-[0_4px_14px_0_rgba(229,193,0,0.39)] hover:shadow-[0_6px_20px_rgba(229,193,0,0.23)] hover:-translate-y-0.5 transition-all duration-300 relative overflow-hidden group/btn text-sm sm:text-base">
                    <span class="absolute inset-0 w-full h-full bg-white/40 -translate-x-full group-hover/btn:animate-[shimmer_1.5s_infinite] skew-x-12"></span>
                    <i class="fa-solid fa-plus relative z-10 text-lg"></i> 
                    <span class="relative z-10">Pinjam Alat Baru</span>
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 hover:border-blue-200 transition-colors relative overflow-hidden group flex flex-col justify-between">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-blue-50 rounded-full group-hover:scale-150 transition-transform duration-500 ease-out z-0"></div>
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-[11px] font-extrabold text-gray-400 mb-1.5 uppercase tracking-widest">Menunggu Izin</p>
                    <h3 class="text-4xl font-black text-gray-800">{{ $menunggu_verifikasi ?? 0 }}</h3>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl font-bold border border-blue-100 group-hover:bg-blue-600 group-hover:text-white group-hover:shadow-lg transition-all duration-300">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
            </div>
            <div class="mt-5 pt-4 border-t border-gray-50 relative z-10">
                <p class="text-xs text-gray-500 font-medium flex items-center gap-1.5">
                    <i class="fa-solid fa-circle-info text-blue-400"></i> Permohonan pinjam sedang direview admin.
                </p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 hover:border-yellow-200 transition-colors relative overflow-hidden group flex flex-col justify-between">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-yellow-50 rounded-full group-hover:scale-150 transition-transform duration-500 ease-out z-0"></div>
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-[11px] font-extrabold text-gray-400 mb-1.5 uppercase tracking-widest">Sedang Dibawa</p>
                    <h3 class="text-4xl font-black text-gray-800">{{ $sedang_dipinjam ?? 0 }}</h3>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-yellow-50 text-yellow-600 flex items-center justify-center text-2xl font-bold border border-yellow-100 group-hover:bg-pln-yellow group-hover:text-gray-900 group-hover:shadow-lg transition-all duration-300">
                    <i class="fa-solid fa-hand-holding-hand"></i>
                </div>
            </div>
            <div class="mt-5 pt-4 border-t border-gray-50 relative z-10">
                <p class="text-xs text-gray-500 font-medium flex items-center gap-1.5">
                    <i class="fa-solid fa-bolt text-yellow-500"></i> Alat yang sedang aktif Anda gunakan saat ini.
                </p>
            </div>
        </div>

    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        
        <div class="xl:col-span-2 space-y-6">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden flex flex-col h-full">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-100">
                            <i class="fa-solid fa-screwdriver-wrench"></i>
                        </div>
                        <h3 class="font-extrabold text-gray-800 text-lg">Alat di Tangan Anda Saat Ini</h3>
                    </div>
                </div>
                
                <div class="p-6 space-y-5 flex-1">
                    @forelse($alat_dipegang as $aktif)
                        <div class="border border-gray-200 rounded-2xl p-5 hover:border-pln-cyan transition-colors bg-white shadow-sm group">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 border-b border-gray-100 pb-4 gap-3">
                                <div>
                                    <span class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-600 border border-blue-100 px-3 py-1 rounded-lg text-xs font-bold font-mono tracking-wider mb-2">
                                        <i class="fa-solid fa-receipt"></i> {{ $aktif->kode_peminjaman }}
                                    </span>
                                    <p class="text-sm font-extrabold text-gray-800 flex items-center gap-2">
                                        <i class="fa-solid fa-location-dot text-red-500"></i> 
                                        {{ $aktif->unit_tujuan->nama_unit ?? 'Lokasi tidak diketahui' }}
                                    </p>
                                </div>
                                <div class="text-left sm:text-right bg-red-50 p-2 sm:bg-transparent sm:p-0 rounded-lg sm:rounded-none">
                                    <p class="text-[10px] text-red-500 font-extrabold uppercase tracking-widest mb-1">Batas Waktu Pinjam</p>
                                    <p class="text-sm font-black text-red-600 flex items-center sm:justify-end gap-1.5">
                                        <i class="fa-regular fa-calendar-xmark"></i> 
                                        {{ \Carbon\Carbon::parse($aktif->estimasi_kembali)->format('d M Y, H:i') }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="space-y-3">
                                <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2">Rincian Fisik Alat ({{ $aktif->detail_peminjaman->count() }} Item):</p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    @foreach($aktif->detail_peminjaman as $detail)
                                        <div class="flex items-center gap-3 bg-gray-50/80 hover:bg-white p-3 rounded-xl border border-gray-100 group-hover:border-blue-100 transition-colors">
                                            <div class="w-10 h-10 rounded-lg bg-white border border-gray-200 flex items-center justify-center text-gray-400 shadow-sm shrink-0">
                                                <i class="fa-solid fa-barcode"></i>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-xs font-mono font-bold text-pln-cyan truncate">{{ $detail->item_inventaris->kode_barcode ?? '-' }}</p>
                                                <p class="text-xs text-gray-600 font-bold truncate">{{ $detail->item_inventaris->peralatan->nama_alat ?? '-' }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            
                            <div class="mt-5 pt-4 border-t border-gray-100 flex justify-end">
                                <a href="{{ route('pegawai.pengembalian.form', $aktif->id) }}" class="px-5 py-2.5 bg-white text-green-600 font-bold text-xs rounded-xl border border-green-600 hover:bg-green-600 hover:text-white transition-all shadow-sm flex items-center gap-2">
                                    <i class="fa-solid fa-rotate-left"></i> Kembalikan Alat
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="h-full flex flex-col items-center justify-center py-12 px-4 text-center bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                            <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-sm mb-4">
                                <i class="fa-solid fa-box-open text-4xl text-gray-300"></i>
                            </div>
                            <h4 class="text-lg font-extrabold text-gray-700 mb-1">Clear! Tidak Ada Pinjaman</h4>
                            <p class="text-sm text-gray-500 font-medium mb-5">Anda tidak sedang meminjam atau membawa alat apapun saat ini.</p>
                            <a href="{{ route('pegawai.katalog.index') }}" class="inline-flex items-center gap-2 text-pln-cyan text-sm font-bold hover:text-[#008Cca] transition">
                                Lihat Katalog Alat <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="space-y-6">
            
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-yellow-50 text-yellow-600 flex items-center justify-center border border-yellow-100">
                            <i class="fa-solid fa-envelope-open-text"></i>
                        </div>
                        <h3 class="font-extrabold text-gray-800 text-lg">Status Permohonan</h3>
                    </div>
                </div>
                
                <div class="p-6 space-y-4">
                    @forelse($permohonan_terkini as $mohon)
                        <div class="p-4 border border-gray-100 rounded-2xl hover:border-blue-200 transition-colors bg-white shadow-sm group">
                            <div class="flex justify-between items-start mb-3">
                                <span class="font-mono text-xs font-bold text-gray-800 bg-gray-100 px-2 py-1 rounded">{{ $mohon->kode_peminjaman }}</span>
                                @if($mohon->status_peminjaman == 'Menunggu Verifikasi')
                                    <span class="px-2.5 py-1 bg-blue-50 text-blue-600 text-[10px] font-extrabold rounded-md border border-blue-100 uppercase tracking-widest flex items-center gap-1"><i class="fa-regular fa-clock"></i> Pending</span>
                                @else
                                    <span class="px-2.5 py-1 bg-red-50 text-red-600 text-[10px] font-extrabold rounded-md border border-red-100 uppercase tracking-widest flex items-center gap-1"><i class="fa-solid fa-ban"></i> Ditolak</span>
                                @endif
                            </div>
                            <p class="text-xs text-gray-800 font-bold line-clamp-1 mb-1 group-hover:text-pln-cyan transition-colors">
                                <i class="fa-solid fa-location-crosshairs text-gray-400 mr-1.5"></i> {{ $mohon->unit_tujuan->nama_unit ?? '-' }}
                            </p>
                            <p class="text-[10px] text-gray-500 font-medium">
                                Diajukan: {{ \Carbon\Carbon::parse($mohon->tanggal_pengajuan)->diffForHumans() }}
                            </p>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <i class="fa-regular fa-folder-open text-3xl text-gray-200 mb-2"></i>
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">Tidak Ada Antrean</p>
                        </div>
                    @endforelse
                </div>
                
                @if(isset($permohonan_terkini) && count($permohonan_terkini) > 0)
                    <div class="p-4 border-t border-gray-100 bg-gray-50/80 text-center">
                        <a href="{{ route('pegawai.riwayat.index') }}" class="text-xs font-extrabold text-pln-cyan hover:text-blue-700 transition uppercase tracking-wider flex items-center justify-center gap-1">
                            Lihat Semua Riwayat <i class="fa-solid fa-chevron-right text-[10px]"></i>
                        </a>
                    </div>
                @endif
            </div>

            <div class="bg-gradient-to-br from-pln-cyan to-blue-600 rounded-3xl shadow-md p-1">
                <div class="bg-white rounded-[22px] p-6 h-full">
                    <h3 class="font-extrabold text-gray-800 text-lg mb-5 flex items-center gap-2">
                        <i class="fa-solid fa-bolt text-pln-yellow"></i> Akses Cepat
                    </h3>
                    
                    <div class="flex flex-col gap-3">
                        <a href="{{ route('pegawai.katalog.index') }}" class="flex items-center p-3 bg-gray-50 border border-gray-100 rounded-xl hover:border-pln-cyan hover:bg-blue-50/50 transition-all group">
                            <div class="w-12 h-12 rounded-xl bg-white flex items-center justify-center text-pln-cyan border border-gray-200 group-hover:border-pln-cyan shadow-sm mr-4 transition-colors shrink-0">
                                <i class="fa-solid fa-toolbox text-lg"></i>
                            </div>
                            <div>
                                <p class="font-extrabold text-sm text-gray-800 group-hover:text-pln-cyan transition-colors">Katalog Alat</p>
                                <p class="text-xs text-gray-500 mt-0.5">Cari dan pinjam peralatan</p>
                            </div>
                            <i class="fa-solid fa-chevron-right ml-auto text-gray-300 group-hover:text-pln-cyan text-xs transition-colors"></i>
                        </a>

                        <a href="{{ route('pegawai.riwayat.index') }}" class="flex items-center p-3 bg-gray-50 border border-gray-100 rounded-xl hover:border-pln-cyan hover:bg-blue-50/50 transition-all group">
                            <div class="w-12 h-12 rounded-xl bg-white flex items-center justify-center text-pln-cyan border border-gray-200 group-hover:border-pln-cyan shadow-sm mr-4 transition-colors shrink-0">
                                <i class="fa-solid fa-receipt text-lg"></i>
                            </div>
                            <div>
                                <p class="font-extrabold text-sm text-gray-800 group-hover:text-pln-cyan transition-colors">Tiket & Riwayat</p>
                                <p class="text-xs text-gray-500 mt-0.5">Pantau status transaksi</p>
                            </div>
                            <i class="fa-solid fa-chevron-right ml-auto text-gray-300 group-hover:text-pln-cyan text-xs transition-colors"></i>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    /* Keyframe efek kilap tombol banner */
    @keyframes shimmer {
        100% { transform: translateX(200%); }
    }
</style>
@endsection