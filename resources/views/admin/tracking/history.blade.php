@extends('layouts.app')

@section('title', 'Jejak Perjalanan Alat')

@section('content')
<div class="w-full space-y-6 pb-10">
    
    <!-- Header Area -->
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-blue-50 text-pln-cyan rounded-2xl flex items-center justify-center text-2xl border-2 border-blue-100 shadow-inner">
                <i class="fa-solid fa-map-location-dot"></i>
            </div>
            <div>
                <h1 class="text-2xl font-extrabold text-gray-800 tracking-tight">Detail Perjalanan Aset</h1>
                <p class="text-sm text-gray-500 font-medium mt-0.5">Lacak histori pergerakan, pemakaian, dan status fisik alat.</p>
            </div>
        </div>
        <a href="{{ route('admin.tracking.index') }}" class="px-6 py-2.5 bg-white text-gray-600 font-bold rounded-xl hover:bg-gray-50 hover:text-gray-800 transition shadow-sm border-2 border-gray-200 flex items-center gap-2">
            <i class="fa-solid fa-arrow-left text-sm"></i> Kembali
        </a>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        
        <!-- Kolom Kiri: Digital ID Card Aset -->
        <div class="xl:col-span-1 space-y-6">
            <div class="bg-gradient-to-br from-gray-900 to-pln-dark rounded-3xl p-1 shadow-xl relative overflow-hidden group">
                <!-- Efek Glow Background -->
                <div class="absolute -bottom-20 -right-20 w-64 h-64 bg-pln-cyan opacity-20 rounded-full blur-3xl group-hover:opacity-40 transition-opacity duration-500"></div>
                <div class="absolute -top-20 -left-20 w-48 h-48 bg-pln-yellow opacity-10 rounded-full blur-3xl"></div>

                <div class="bg-gray-900/40 backdrop-blur-sm rounded-[22px] p-6 h-full relative z-10 border border-white/10">
                    
                    <!-- Header Card & Kode Barang -->
                    <div class="flex justify-between items-start mb-6">
                        <div class="w-12 h-12 bg-white/10 backdrop-blur-md rounded-xl flex items-center justify-center border border-white/20 shadow-inner">
                            <i class="fa-solid fa-hashtag text-pln-yellow text-xl"></i>
                        </div>
                        <span class="px-4 py-1.5 bg-white/10 border border-white/20 rounded-full text-xs font-extrabold text-white tracking-widest uppercase shadow-sm">
                            <i class="fa-solid fa-qrcode mr-1"></i> {{ $item->kode_barcode }}
                        </span>
                    </div>
                    
                    <!-- Info Alat -->
                    <div class="mb-8">
                        <h2 class="text-2xl font-extrabold text-white leading-tight mb-2 group-hover:text-pln-cyan transition-colors">{{ $item->peralatan->nama_alat }}</h2>
                        <p class="text-sm text-gray-400 font-medium line-clamp-3">{{ $item->peralatan->spesifikasi ?? 'Tidak ada deskripsi spesifikasi untuk alat ini.' }}</p>
                    </div>

                    <!-- Spesifikasi Status -->
                    <div class="space-y-4 pt-6 border-t border-white/10">
                        <div class="flex justify-between items-center bg-black/20 p-3 rounded-xl border border-white/5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-gray-400"><i class="fa-solid fa-heart-pulse"></i></div>
                                <span class="text-sm font-bold text-gray-300">Kondisi Fisik</span>
                            </div>
                            <span class="px-3 py-1 rounded-lg text-xs font-extrabold {{ $item->kondisi == 'Baik' ? 'bg-green-500/20 text-green-400 border border-green-500/30' : 'bg-red-500/20 text-red-400 border border-red-500/30' }}">
                                {{ $item->kondisi }}
                            </span>
                        </div>

                        <div class="flex justify-between items-center bg-black/20 p-3 rounded-xl border border-white/5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-gray-400"><i class="fa-solid fa-boxes-stacked"></i></div>
                                <span class="text-sm font-bold text-gray-300">Status Ketersediaan</span>
                            </div>
                            <span class="text-sm font-extrabold text-pln-cyan">{{ $item->status_ketersediaan }}</span>
                        </div>

                        <div class="flex justify-between items-center bg-black/20 p-3 rounded-xl border border-white/5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-gray-400"><i class="fa-solid fa-layer-group"></i></div>
                                <span class="text-sm font-bold text-gray-300">Lokasi Rak</span>
                            </div>
                            <span class="text-sm font-bold text-white text-right">{{ $item->peralatan->rak->nama_rak ?? 'Tidak terdata' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Timeline Pergerakan -->
        <div class="xl:col-span-2">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gray-50/80 border-b border-gray-100 p-6 flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shadow-inner">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                        </div>
                        <h3 class="font-extrabold text-gray-800 text-lg">Histori Aktivitas</h3>
                    </div>
                    <span class="px-4 py-1.5 bg-gray-200 text-gray-600 font-bold text-xs rounded-full uppercase tracking-wider">
                        {{ $logs->count() }} Record
                    </span>
                </div>

                <div class="p-6 md:p-8">
                    @if($logs->count() > 0)
                    <!-- Container Timeline -->
                    <div class="relative border-l-2 border-gray-200 ml-3 sm:ml-5 space-y-8 pb-4">
                        
                        @foreach($logs as $index => $log)
                        <div class="relative pl-6 sm:pl-10 group">
                            
                            <!-- Titik Timeline (Warna dinamis tergantung ada tidaknya unit_lokasi) -->
                            @php
                                $dotColor = $log->unit_lokasi ? 'bg-blue-500 ring-blue-100' : 'bg-green-500 ring-green-100';
                                $iconColor = $log->unit_lokasi ? 'text-blue-600 bg-blue-50 border-blue-100' : 'text-green-600 bg-green-50 border-green-100';
                            @endphp
                            
                            <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full {{ $dotColor }} ring-4 transition-all duration-300 group-hover:scale-125"></div>

                            <!-- Card Konten Timeline -->
                            <div class="bg-white p-5 rounded-2xl border-2 border-gray-100 shadow-sm hover:shadow-md hover:border-blue-200 transition-all duration-300">
                                
                                <!-- Header Card -->
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                                    <div class="flex items-center gap-2 text-sm font-bold text-gray-800">
                                        <i class="fa-solid fa-calendar-day text-gray-400"></i>
                                        {{ \Carbon\Carbon::parse($log->tanggal_waktu)->translatedFormat('d F Y') }}
                                        <span class="text-xs text-gray-400 font-medium ml-1"><i class="fa-regular fa-clock"></i> {{ \Carbon\Carbon::parse($log->tanggal_waktu)->format('H:i:s') }} WIB</span>
                                    </div>
                                    
                                    @if($log->peminjaman_id)
                                        <a href="{{ route('admin.peminjaman.show', $log->peminjaman_id) }}" class="inline-flex items-center gap-1.5 text-[10px] font-extrabold uppercase tracking-wider bg-gray-100 text-gray-600 hover:bg-pln-cyan hover:text-white px-3 py-1.5 rounded-lg transition-colors">
                                            <i class="fa-solid fa-receipt"></i> TRX-{{ explode('-', $log->peminjaman->kode_peminjaman)[2] ?? $log->peminjaman_id }}
                                        </a>
                                    @endif
                                </div>

                                <!-- Aktivitas Utama -->
                                <p class="text-base font-extrabold text-gray-800 mb-4">{{ $log->aktivitas }}</p>

                                <!-- Footer Card (Info Pelaku & Lokasi) -->
                                <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-100">
                                    
                                    <!-- Pelaku -->
                                    <div class="flex-1 flex items-center gap-3 bg-gray-50 p-3 rounded-xl border border-gray-100">
                                        <div class="w-8 h-8 rounded-full bg-white text-gray-500 border border-gray-200 flex items-center justify-center text-xs shadow-sm">
                                            <i class="fa-solid fa-user-tie"></i>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Penanggung Jawab</p>
                                            <p class="text-xs font-bold text-gray-700">{{ $log->user->nama_lengkap ?? $log->user->name ?? 'Sistem / Anonim' }}</p>
                                        </div>
                                    </div>

                                    <!-- Lokasi -->
                                    <div class="flex-1 flex items-center gap-3 {{ $iconColor }} p-3 rounded-xl border">
                                        <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-xs shadow-sm">
                                            @if($log->unit_lokasi)
                                                <i class="fa-solid fa-location-dot"></i>
                                            @else
                                                <i class="fa-solid fa-warehouse"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-bold uppercase tracking-wider mb-0.5 opacity-70">Posisi Alat</p>
                                            <p class="text-xs font-bold">
                                                @if($log->unit_lokasi)
                                                    {{ $log->unit_lokasi->nama_unit }}
                                                @else
                                                    Kembali ke Gudang Induk
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        @endforeach

                    </div>
                    @else
                    <!-- State Kosong -->
                    <div class="text-center py-16 bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200">
                        <div class="w-20 h-20 bg-white shadow-sm border border-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fa-solid fa-shoe-prints text-3xl text-gray-300"></i>
                        </div>
                        <h3 class="text-lg font-extrabold text-gray-700 mb-1">Belum Ada Jejak</h3>
                        <p class="text-sm text-gray-500 font-medium">Alat ini belum pernah dipinjam atau dipindahkan sejak didaftarkan.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
@endsection