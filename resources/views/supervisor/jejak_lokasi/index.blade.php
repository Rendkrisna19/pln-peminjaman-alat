@extends('layouts.app')
@section('title', 'Cari Jejak Lokasi Alat')

@section('content')
<div class="w-full space-y-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800"><i class="fa-solid fa-map-location-dot text-pln-cyan mr-2"></i> Jejak Lokasi Alat</h1>
            <p class="text-sm text-gray-500 mt-1">Lacak riwayat pergerakan dan peminjaman alat fisik berdasarkan Kode Barang.</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-3xl border-2 border-gray-200 shadow-sm">
        <form action="{{ route('supervisor.jejak.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <i class="fa-solid fa-hashtag absolute left-4 top-3.5 text-gray-400 text-lg"></i>
                <input type="text" name="search" value="{{ $search }}" placeholder="Ketik Kode Barang (cth: PAND-001)..." 
                       class="w-full pl-12 pr-4 py-3 rounded-xl border-2 border-gray-200 focus:border-pln-cyan focus:ring-0 text-gray-800 font-bold transition">
            </div>
            <button type="submit" class="px-8 py-3 bg-pln-cyan text-white font-bold rounded-xl shadow-lg shadow-pln-cyan/30 hover:bg-[#008Cca] transition transform hover:-translate-y-0.5">
                <i class="fa-solid fa-magnifying-glass mr-2"></i> Cari Alat
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($items as $item)
        <div class="bg-white p-6 rounded-3xl border-2 border-gray-100 shadow-sm hover:border-pln-cyan hover:shadow-md transition-all group flex flex-col justify-between">
            <div>
                <div class="flex justify-between items-start mb-4">
                    <span class="px-3 py-1 bg-gray-100 border border-gray-200 rounded-lg text-xs font-mono font-bold text-gray-800">
                        <i class="fa-solid fa-hashtag text-gray-400 mr-1"></i> {{ $item->kode_barcode }}
                    </span>
                    @if($item->status_ketersediaan == 'Tersedia')
                        <span class="w-3 h-3 bg-green-500 rounded-full shadow-[0_0_8px_rgba(34,197,94,0.6)] animate-pulse"></span>
                    @elseif($item->status_ketersediaan == 'Dipinjam')
                        <span class="w-3 h-3 bg-yellow-500 rounded-full shadow-[0_0_8px_rgba(234,179,8,0.6)]"></span>
                    @else
                        <span class="w-3 h-3 bg-red-500 rounded-full shadow-[0_0_8px_rgba(239,68,68,0.6)]"></span>
                    @endif
                </div>
                <h3 class="text-lg font-bold text-gray-800 line-clamp-1 mb-1">{{ $item->peralatan->nama_alat ?? 'Alat Dihapus' }}</h3>
                <p class="text-xs text-gray-500 font-medium mb-4"><i class="fa-solid fa-server mr-1"></i> {{ $item->peralatan->rak->nama_rak ?? 'Tanpa Rak' }}</p>
            </div>
            
            <a href="{{ route('supervisor.jejak.show', $item->id) }}" class="w-full py-2.5 bg-gray-50 text-pln-cyan border-2 border-gray-200 rounded-xl font-bold text-sm flex justify-center items-center gap-2 group-hover:bg-pln-cyan group-hover:text-white group-hover:border-pln-cyan transition-colors">
                Lacak Riwayat <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
        @empty
        <div class="col-span-full py-12 text-center bg-white rounded-3xl border-2 border-dashed border-gray-300">
            <i class="fa-solid fa-box-open text-5xl text-gray-300 mb-4 block"></i>
            <p class="text-gray-500 font-bold">Tidak ada alat yang cocok dengan pencarian Anda.</p>
        </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $items->links() }}
    </div>
</div>
@endsection