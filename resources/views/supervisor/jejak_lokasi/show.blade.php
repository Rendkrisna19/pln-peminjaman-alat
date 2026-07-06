@extends('layouts.app')
@section('title', 'Detail Jejak Alat')

@section('content')
<div class="w-full max-w-5xl mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('supervisor.jejak.index') }}" class="p-3 bg-white border-2 border-gray-200 rounded-xl hover:bg-gray-50 transition shadow-sm text-gray-600">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Detail Jejak Alat</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-pln-dark p-6 rounded-3xl border-2 border-gray-800 shadow-xl text-white relative overflow-hidden">
                <div class="absolute -right-6 -top-6 opacity-10">
                    <i class="fa-solid fa-microchip text-9xl text-white"></i>
                </div>
                <div class="relative z-10">
                    <span class="px-3 py-1 bg-white/10 border border-white/20 rounded-lg text-xs font-mono font-bold text-pln-yellow mb-4 inline-block">
                        <i class="fa-solid fa-hashtag mr-1"></i> {{ $item->kode_barcode }}
                    </span>
                    <h2 class="text-2xl font-black mb-1">{{ $item->peralatan->nama_alat ?? 'Nama Alat' }}</h2>
                    <p class="text-sm text-gray-400 font-medium mb-6">{{ $item->peralatan->spesifikasi ?? 'Tanpa Spesifikasi' }}</p>
                    
                    <div class="space-y-3 pt-4 border-t border-white/10">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-400">Kondisi:</span>
                            <span class="font-bold text-white">{{ $item->kondisi }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-400">Status:</span>
                            <span class="font-bold text-pln-cyan">{{ $item->status_ketersediaan }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-400">Rak:</span>
                            <span class="font-bold text-white">{{ $item->peralatan->rak->nama_rak ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white p-6 md:p-8 rounded-3xl border-2 border-gray-200 shadow-sm">
                <h3 class="font-bold text-gray-800 mb-8 uppercase tracking-widest text-sm flex items-center gap-2">
                    <i class="fa-solid fa-timeline text-pln-cyan"></i> Riwayat Pergerakan
                </h3>

                <div class="relative border-l-4 border-gray-100 ml-4 space-y-8">
                    @forelse($logs as $log)
                    <div class="relative pl-8">
                        @if($log->status_tracking == 'Dipinjam')
                            <div class="absolute -left-[14px] top-1 w-6 h-6 bg-yellow-100 border-4 border-white rounded-full flex items-center justify-center shadow-sm">
                                <div class="w-2.5 h-2.5 bg-yellow-500 rounded-full"></div>
                            </div>
                        @elseif($log->status_tracking == 'Dikembalikan')
                            <div class="absolute -left-[14px] top-1 w-6 h-6 bg-green-100 border-4 border-white rounded-full flex items-center justify-center shadow-sm">
                                <div class="w-2.5 h-2.5 bg-green-500 rounded-full"></div>
                            </div>
                        @else
                            <div class="absolute -left-[14px] top-1 w-6 h-6 bg-pln-cyan/20 border-4 border-white rounded-full flex items-center justify-center shadow-sm">
                                <div class="w-2.5 h-2.5 bg-pln-cyan rounded-full"></div>
                            </div>
                        @endif

                        <div class="bg-gray-50 border-2 border-gray-100 rounded-2xl p-5 hover:border-pln-cyan hover:shadow-md transition-all">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-3 gap-2">
                                <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider 
                                    {{ $log->status_tracking == 'Dipinjam' ? 'bg-yellow-200 text-yellow-800' : ($log->status_tracking == 'Dikembalikan' ? 'bg-green-200 text-green-800' : 'bg-blue-200 text-blue-800') }}">
                                    {{ $log->status_tracking }}
                                </span>
                                <span class="text-xs font-bold text-gray-400">
                                    <i class="fa-regular fa-clock"></i> {{ \Carbon\Carbon::parse($log->tanggal_waktu)->format('d M Y, H:i') }}
                                </span>
                            </div>
                            
                            <h4 class="font-bold text-gray-800 mb-1">
                                Oleh: <span class="text-pln-cyan">{{ $log->user->nama_lengkap ?? 'User Dihapus' }}</span>
                            </h4>
                            
                            @if($log->unit_lokasi_id)
                                <p class="text-sm text-gray-600 font-medium flex items-center gap-2 mt-2">
                                    <i class="fa-solid fa-location-dot text-red-500"></i> Tujuan: {{ $log->unit_lokasi->nama_unit }}
                                </p>
                            @endif

                            @if($log->peminjaman)
                                <div class="mt-3 pt-3 border-t-2 border-gray-200">
                                    <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-1">Keterangan Tugas:</p>
                                    <p class="text-sm text-gray-700 italic">{{ $log->peminjaman->keterangan_pekerjaan ?? '-' }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="pl-8 text-center py-8">
                        <i class="fa-solid fa-box-archive text-4xl text-gray-200 mb-3 block"></i>
                        <p class="text-gray-500 font-medium text-sm">Alat ini belum memiliki riwayat pergerakan (masih di gudang).</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>
@endsection