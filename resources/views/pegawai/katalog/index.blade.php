@extends('layouts.app')

@section('title', 'Katalog Alat')

@section('content')
<div class="w-full space-y-6 pb-10">
    
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-blue-50 text-pln-cyan rounded-2xl flex items-center justify-center text-2xl border-2 border-blue-100 shadow-inner">
                <i class="fa-solid fa-toolbox"></i>
            </div>
            <div>
                <h1 class="text-2xl font-extrabold text-gray-800 tracking-tight">Katalog Peralatan Kerja</h1>
                <p class="text-sm text-gray-500 font-medium mt-0.5">Cari, pilih, dan ajukan peminjaman alat dengan mudah.</p>
            </div>
        </div>
    </div>

    <!-- Filter & Pencarian -->
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200">
        <form action="{{ route('pegawai.katalog.index') }}" method="GET" class="flex flex-col lg:flex-row gap-4">
            
            <!-- Pencarian Text -->
            <div class="relative flex-1">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-3.5 text-gray-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama alat atau spesifikasi..." 
                    class="w-full pl-11 pr-4 py-3 rounded-xl border-2 border-gray-100 focus:border-pln-cyan focus:ring-0 text-sm font-bold text-gray-700 shadow-sm transition hover:border-gray-200">
            </div>

            <!-- Dropdown Rak -->
            <div class="relative lg:w-64">
                <i class="fa-solid fa-layer-group absolute left-4 top-3.5 text-gray-400"></i>
                <select name="rak_id" class="w-full pl-11 pr-10 py-3 rounded-xl border-2 border-gray-100 focus:border-pln-cyan focus:ring-0 text-sm font-bold text-gray-700 appearance-none bg-white shadow-sm cursor-pointer transition hover:border-gray-200">
                    <option value="">Semua Rak / Lokasi</option>
                    @foreach($daftarRak ?? [] as $rak)
                        <option value="{{ $rak->id }}" {{ request('rak_id') == $rak->id ? 'selected' : '' }}>
                            {{ $rak->nama_rak }}
                        </option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                    <i class="fa-solid fa-chevron-down text-xs"></i>
                </div>
            </div>

            <!-- Dropdown Status -->
            <div class="relative lg:w-48">
                <i class="fa-solid fa-box absolute left-4 top-3.5 text-gray-400"></i>
                <select name="status" class="w-full pl-11 pr-10 py-3 rounded-xl border-2 border-gray-100 focus:border-pln-cyan focus:ring-0 text-sm font-bold text-gray-700 appearance-none bg-white shadow-sm cursor-pointer transition hover:border-gray-200">
                    <option value="">Semua Status</option>
                    <option value="tersedia" {{ request('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                    <option value="habis" {{ request('status') == 'habis' ? 'selected' : '' }}>Stok Habis</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                    <i class="fa-solid fa-chevron-down text-xs"></i>
                </div>
            </div>

            <!-- Tombol Filter -->
            <div class="flex gap-2">
                <button type="submit" class="px-8 py-3 bg-pln-dark text-white font-extrabold rounded-xl border-2 border-pln-dark hover:bg-gray-800 transition shadow-md flex items-center justify-center gap-2">
                    <i class="fa-solid fa-filter text-sm"></i> Filter
                </button>
                @if(request('search') || request('rak_id') || request('status'))
                    <a href="{{ route('pegawai.katalog.index') }}" class="px-4 py-3 bg-gray-50 text-red-500 font-bold rounded-xl border-2 border-gray-200 hover:bg-red-50 hover:border-red-200 hover:text-red-600 transition shadow-sm flex items-center justify-center" title="Hapus Filter">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Grid Katalog Alat -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($peralatan as $item)
        <div class="bg-white rounded-2xl border-2 border-gray-100 shadow-sm overflow-hidden hover:border-pln-cyan hover:shadow-xl transition-all duration-300 group flex flex-col relative">
            
            <!-- Area Gambar -->
            <div class="h-56 overflow-hidden bg-gray-50 relative border-b-2 border-gray-100">
                @if($item->foto)
                    <img src="{{ asset('storage/' . $item->foto) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-in-out">
                @else
                    <div class="w-full h-full flex flex-col items-center justify-center text-gray-300">
                        <i class="fa-solid fa-image text-5xl"></i>
                        <span class="text-xs mt-3 font-extrabold uppercase tracking-widest text-gray-400">No Image</span>
                    </div>
                @endif
                
                <!-- Badge Stok -->
                <div class="absolute top-3 left-3 flex flex-col gap-1.5">
                    <span class="px-3 py-1.5 {{ $item->stok_tersedia > 0 ? 'bg-green-500 text-white border-green-600' : 'bg-red-50 text-red-600 border-red-200' }} border text-[11px] font-extrabold rounded-lg shadow-sm uppercase tracking-wider flex items-center gap-1 backdrop-blur-md bg-opacity-90">
                        @if($item->stok_tersedia > 0)
                            <i class="fa-solid fa-check-circle"></i> Ready: {{ $item->stok_tersedia }}
                        @else
                            <i class="fa-solid fa-ban"></i> Stok Habis
                        @endif
                    </span>
                </div>
            </div>

            <!-- Area Detail -->
            <div class="p-5 flex-1 flex flex-col relative bg-white z-10">
                <div class="flex items-center gap-2 mb-2">
                    <span class="px-2 py-0.5 bg-gray-100 text-gray-500 text-[10px] font-bold uppercase rounded border border-gray-200">
                        {{ $item->rak->nama_rak ?? 'Tidak ada rak' }}
                    </span>
                </div>
                
                <h3 class="font-extrabold text-gray-800 text-lg leading-tight line-clamp-2 mb-2 group-hover:text-pln-cyan transition-colors">{{ $item->nama_alat }}</h3>
                <p class="text-xs text-gray-500 line-clamp-3 mb-5 font-medium leading-relaxed">{{ $item->spesifikasi }}</p>
                
                <div class="mt-auto">
                    @if($item->stok_tersedia > 0)
                    <form action="{{ route('pegawai.katalog.form', $item->id) }}" method="GET" class="flex items-end gap-3 pt-4 border-t border-gray-100">
                        <div class="w-20">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Set Qty</label>
                            <input type="number" name="qty" value="1" min="1" max="{{ $item->stok_tersedia }}" class="w-full px-2 py-2.5 rounded-xl border-2 border-gray-200 focus:border-pln-cyan focus:ring-0 text-center text-sm font-bold transition hover:border-gray-300 shadow-sm">
                        </div>
                        <button type="submit" class="flex-1 py-2.5 bg-white text-pln-cyan font-extrabold rounded-xl border-2 border-pln-cyan hover:bg-pln-cyan hover:text-white transition-all duration-300 shadow-sm flex items-center justify-center gap-2 group/btn relative overflow-hidden">
                            <span class="absolute inset-0 w-full h-full bg-white/20 -translate-x-full group-hover/btn:animate-[shimmer_1.5s_infinite] skew-x-12"></span>
                            <i class="fa-solid fa-cart-arrow-down relative z-10"></i> 
                            <span class="relative z-10">Pinjam</span>
                        </button>
                    </form>
                    @else
                    <div class="pt-4 border-t border-gray-100">
                        <button disabled class="w-full py-3 bg-gray-50 text-gray-400 font-extrabold rounded-xl border-2 border-gray-100 cursor-not-allowed text-sm flex items-center justify-center gap-2">
                            <i class="fa-solid fa-lock"></i> Tidak Tersedia
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-24 text-center bg-white border-2 border-dashed border-gray-300 rounded-3xl flex flex-col items-center justify-center shadow-sm">
            <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mb-4 border-4 border-white shadow-md">
                <i class="fa-solid fa-box-open text-4xl text-gray-300"></i>
            </div>
            <h3 class="text-xl font-extrabold text-gray-700 mb-2">Peralatan Tidak Ditemukan</h3>
            <p class="text-gray-500 font-medium">Coba ubah kata kunci pencarian atau sesuaikan filter Anda.</p>
            @if(request('search') || request('rak_id') || request('status'))
                <a href="{{ route('pegawai.katalog.index') }}" class="mt-6 px-6 py-2.5 bg-blue-50 text-blue-600 font-bold rounded-xl hover:bg-blue-100 transition border border-blue-200">
                    Reset Semua Filter
                </a>
            @endif
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-8 bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
        {{ $peralatan->links() }}
    </div>
</div>

<style>
    /* Efek kilap pada tombol Pinjam */
    @keyframes shimmer {
        100% { transform: translateX(200%); }
    }
</style>
@endsection