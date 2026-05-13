@extends('layouts.app')
@section('title', 'Laporan Kondisi Fisik Aset')

@section('content')
<div class="w-full space-y-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800"><i class="fa-solid fa-shield-virus text-pln-cyan mr-2"></i> Laporan Kondisi Fisik Aset</h1>
            <p class="text-sm text-gray-500 mt-1">Monitoring kesehatan inventaris PT PLN (Persero) UP Pandan.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-3xl border-2 border-blue-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-50 text-pln-cyan rounded-2xl flex items-center justify-center text-xl font-bold border border-blue-100 shrink-0"><i class="fa-solid fa-boxes-stacked"></i></div>
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Total Aset</p>
                <h3 class="text-2xl font-black text-gray-800">{{ $stats['total'] }}</h3>
            </div>
        </div>
        <div class="bg-white p-5 rounded-3xl border-2 border-green-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center text-xl font-bold border border-green-100 shrink-0"><i class="fa-solid fa-square-check"></i></div>
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Kondisi Baik</p>
                <h3 class="text-2xl font-black text-green-600">{{ $stats['baik'] }}</h3>
            </div>
        </div>
        <div class="bg-white p-5 rounded-3xl border-2 border-yellow-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-yellow-50 text-yellow-600 rounded-2xl flex items-center justify-center text-xl font-bold border border-yellow-200 shrink-0"><i class="fa-solid fa-screwdriver-wrench"></i></div>
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Rusak Ringan</p>
                <h3 class="text-2xl font-black text-yellow-600">{{ $stats['rusak_ringan'] }}</h3>
            </div>
        </div>
        <div class="bg-white p-5 rounded-3xl border-2 border-red-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-red-50 text-red-600 rounded-2xl flex items-center justify-center text-xl font-bold border border-red-100 shrink-0"><i class="fa-solid fa-triangle-exclamation animate-pulse"></i></div>
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Rusak Berat</p>
                <h3 class="text-2xl font-black text-red-600">{{ $stats['rusak_berat'] }}</h3>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-3xl border-2 border-gray-200 shadow-sm flex flex-col lg:flex-row justify-between items-center gap-4">
        <form action="{{ route('supervisor.laporan.aset') }}" method="GET" class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto flex-1">
            <div class="relative w-full sm:w-64">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-3 text-gray-400"></i>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari barcode / nama alat..." class="w-full pl-11 pr-4 py-2.5 rounded-xl border-2 border-gray-100 focus:border-pln-cyan focus:ring-0 text-sm font-bold">
            </div>
            <select name="kondisi" class="w-full sm:w-48 px-4 py-2.5 rounded-xl border-2 border-gray-100 focus:border-pln-cyan focus:ring-0 text-sm font-bold text-gray-700">
                <option value="">Semua Kondisi</option>
                <option value="Baik" {{ $kondisi == 'Baik' ? 'selected' : '' }}>Baik</option>
                <option value="Rusak Ringan" {{ $kondisi == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                <option value="Rusak Berat" {{ $kondisi == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
            </select>
            <button type="submit" class="px-5 py-2.5 bg-pln-dark text-white rounded-xl font-bold text-sm hover:bg-gray-800 transition">Filter</button>
        </form>

        <div class="flex gap-2 w-full lg:w-auto">
            <a target="_blank" href="{{ route('supervisor.laporan.aset.pdf', request()->query()) }}" class="flex-1 lg:flex-none px-5 py-2.5 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition flex items-center justify-center gap-2 text-sm shadow-md">
                <i class="fa-solid fa-file-pdf"></i> Export PDF
            </a>
            <a href="{{ route('supervisor.laporan.aset.excel', request()->query()) }}" class="flex-1 lg:flex-none px-5 py-2.5 bg-[#107C41] text-white font-bold rounded-xl hover:bg-[#0e6b38] transition flex items-center justify-center gap-2 text-sm shadow-md">
                <i class="fa-solid fa-file-excel"></i> Export Excel
            </a>
        </div>
    </div>

    <div class="bg-white rounded-3xl border-2 border-gray-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b-2 border-gray-100 text-gray-500 text-xs uppercase font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-4">KODE BARCODE</th>
                        <th class="px-6 py-4">NAMA ALAT</th>
                        <th class="px-6 py-4">LOKASI RAK</th>
                        <th class="px-6 py-4 text-center">KONDISI FISIK</th>
                        <th class="px-6 py-4 text-center">STATUS</th>
                    </tr>
                </thead>
                <tbody class="divide-y-2 divide-gray-50 text-sm">
                    @forelse($aset as $item)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <span class="inline-block px-3 py-1 bg-white border border-gray-200 rounded-lg font-mono font-bold text-gray-800 shadow-sm">
                                {{ $item->kode_barcode }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-bold text-gray-800">{{ $item->peralatan->nama_alat ?? '-' }}</p>
                            <p class="text-[10px] text-gray-400 font-medium">{{ $item->peralatan->spesifikasi ?? '-' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-bold text-gray-600"><i class="fa-solid fa-server text-gray-300 mr-1"></i> {{ $item->peralatan->rak->nama_rak ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($item->kondisi == 'Baik')
                                <span class="px-3 py-1 bg-green-50 text-green-600 font-black rounded-lg border-2 border-green-200 text-[10px] uppercase">Baik</span>
                            @elseif($item->kondisi == 'Rusak Ringan')
                                <span class="px-3 py-1 bg-yellow-50 text-yellow-600 font-black rounded-lg border-2 border-yellow-200 text-[10px] uppercase">Rusak Ringan</span>
                            @else
                                <span class="px-3 py-1 bg-red-50 text-red-600 font-black rounded-lg border-2 border-red-200 text-[10px] uppercase">Rusak Berat</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">{{ $item->status_ketersediaan }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400 italic">Data aset tidak ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-5 border-t-2 border-gray-100">
            {{ $aset->links() }}
        </div>
    </div>
</div>
@endsection