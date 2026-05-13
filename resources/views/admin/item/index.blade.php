@extends('layouts.app')

@section('title', 'Item Inventaris Fisik')

@php
    // Helper function untuk generate URL Sorting dan Icon Panah
    function sortUrl($field, $currentField, $currentDirection) {
        $direction = ($currentField === $field && $currentDirection === 'asc') ? 'desc' : 'asc';
        return request()->fullUrlWithQuery(['sort_field' => $field, 'sort_direction' => $direction]);
    }

    function sortIcon($field, $currentField, $currentDirection) {
        if ($currentField !== $field) return '<i class="fa-solid fa-sort text-white/50 ml-2"></i>';
        return $currentDirection === 'asc' ? '<i class="fa-solid fa-sort-up ml-2 text-pln-yellow"></i>' : '<i class="fa-solid fa-sort-down ml-2 text-pln-yellow"></i>';
    }
@endphp

@section('content')
<div class="w-full space-y-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800"><i class="fa-solid fa-barcode text-pln-cyan mr-2"></i> Data Item Fisik (Barcode)</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola data fisik setiap unit alat berdasarkan Barcode untuk keperluan tracking.</p>
        </div>
        <a href="{{ route('admin.item-inventaris.create') }}" class="px-5 py-2.5 bg-pln-cyan text-white font-semibold rounded-xl shadow-md hover:bg-[#008Cca] transition flex items-center gap-2 border-2 border-[#008Cca]">
            <i class="fa-solid fa-plus"></i> Tambah Barcode
        </a>
    </div>

    <div class="bg-white p-5 rounded-2xl shadow-sm border-2 border-gray-200">
        <form method="GET" action="{{ route('admin.item-inventaris.index') }}" class="flex flex-col md:flex-row gap-4">
            <input type="hidden" name="sort_field" value="{{ $sortField }}">
            <input type="hidden" name="sort_direction" value="{{ $sortDirection }}">

            <div class="flex-1 relative">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-3.5 text-gray-400"></i>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari Barcode (cth: PLNU-001) atau Nama Alat..." 
                    class="w-full pl-11 pr-4 py-2.5 rounded-xl border-2 border-gray-200 focus:border-pln-cyan focus:ring-0 text-sm transition-colors font-medium">
            </div>
            
            <div class="md:w-64">
                <select name="kondisi" class="w-full px-4 py-2.5 rounded-xl border-2 border-gray-200 focus:border-pln-cyan focus:ring-0 text-sm transition-colors font-medium">
                    <option value="">Semua Kondisi Fisik</option>
                    <option value="Baik" {{ $filterKondisi == 'Baik' ? 'selected' : '' }}>Kondisi: Baik</option>
                    <option value="Rusak Ringan" {{ $filterKondisi == 'Rusak Ringan' ? 'selected' : '' }}>Kondisi: Rusak Ringan</option>
                    <option value="Rusak Berat" {{ $filterKondisi == 'Rusak Berat' ? 'selected' : '' }}>Kondisi: Rusak Berat</option>
                </select>
            </div>

            <button type="submit" class="px-6 py-2.5 bg-gray-800 text-white rounded-xl shadow-md hover:bg-gray-700 transition font-bold text-sm flex items-center justify-center gap-2 border-2 border-gray-800">
                <i class="fa-solid fa-filter"></i> Terapkan
            </button>
            @if($search || $filterKondisi)
                <a href="{{ route('admin.item-inventaris.index') }}" class="px-6 py-2.5 bg-white text-red-600 rounded-xl hover:bg-red-50 transition font-bold text-sm border-2 border-red-200 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-rotate-left"></i> Reset
                </a>
            @endif
        </form>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border-2 border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-pln-cyan text-white text-xs uppercase font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-4 border-r border-white/20">
                            <a href="{{ sortUrl('kode_barcode', $sortField, $sortDirection) }}" class="flex items-center hover:text-pln-yellow transition group">
                                KODE BARCODE {!! sortIcon('kode_barcode', $sortField, $sortDirection) !!}
                            </a>
                        </th>
                        <th class="px-6 py-4 border-r border-white/20">Nama Induk Alat</th>
                        <th class="px-6 py-4 border-r border-white/20 text-center">
                            <a href="{{ sortUrl('kondisi', $sortField, $sortDirection) }}" class="flex items-center justify-center hover:text-pln-yellow transition group">
                                KONDISI FISIK {!! sortIcon('kondisi', $sortField, $sortDirection) !!}
                            </a>
                        </th>
                        <th class="px-6 py-4 border-r border-white/20 text-center">
                            <a href="{{ sortUrl('status_ketersediaan', $sortField, $sortDirection) }}" class="flex items-center justify-center hover:text-pln-yellow transition group">
                                STATUS {!! sortIcon('status_ketersediaan', $sortField, $sortDirection) !!}
                            </a>
                        </th>
                        <th class="px-6 py-4 text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-sm">
                    @forelse($item_inventaris as $item)
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        <td class="px-6 py-4">
                            <span class="inline-block px-3 py-1 bg-gray-100 text-gray-800 border-2 border-gray-300 rounded-lg font-mono font-bold text-sm tracking-wider">
                                {{ $item->kode_barcode }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-bold text-gray-700">
                            {{ $item->peralatan->nama_alat ?? 'Data Alat Dihapus' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($item->kondisi == 'Baik')
                                <span class="px-3 py-1 bg-green-100 text-green-700 font-bold rounded-full border-2 border-green-200 text-xs"><i class="fa-solid fa-check mr-1"></i> Baik</span>
                            @elseif($item->kondisi == 'Rusak Ringan')
                                <span class="px-3 py-1 bg-orange-100 text-orange-700 font-bold rounded-full border-2 border-orange-200 text-xs"><i class="fa-solid fa-wrench mr-1"></i> Rusak Ringan</span>
                            @else
                                <span class="px-3 py-1 bg-red-100 text-red-700 font-bold rounded-full border-2 border-red-200 text-xs"><i class="fa-solid fa-triangle-exclamation mr-1"></i> Rusak Berat</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($item->status_ketersediaan == 'Tersedia')
                                <span class="px-3 py-1 bg-blue-50 text-blue-600 font-bold rounded-lg border-2 border-blue-200 text-xs">Tersedia</span>
                            @elseif($item->status_ketersediaan == 'Dipinjam')
                                <span class="px-3 py-1 bg-pln-yellow/20 text-yellow-700 font-bold rounded-lg border-2 border-yellow-300 text-xs"><i class="fa-solid fa-hand-holding mr-1"></i> Dipinjam</span>
                            @else
                                <span class="px-3 py-1 bg-gray-100 text-gray-600 font-bold rounded-lg border-2 border-gray-300 text-xs"><i class="fa-solid fa-wrench mr-1"></i> Diperbaiki</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.item-inventaris.edit', $item->id) }}" class="p-2 text-blue-600 bg-white rounded-lg hover:bg-blue-50 border-2 border-blue-200 transition" title="Edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <button type="button" onclick="confirmDelete({{ $item->id }})" class="p-2 text-red-600 bg-white rounded-lg hover:bg-red-50 border-2 border-red-200 transition" title="Hapus">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                                <form id="delete-form-{{ $item->id }}" action="{{ route('admin.item-inventaris.destroy', $item->id) }}" method="POST" class="hidden">
                                    @csrf @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400 italic">
                            <i class="fa-solid fa-barcode text-4xl mb-3 block opacity-50"></i>
                            Data barcode/item fisik belum ada.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-5 border-t-2 border-gray-200 bg-gray-50/50">
            {{ $item_inventaris->links() }}
        </div>
    </div>
</div>
@endsection