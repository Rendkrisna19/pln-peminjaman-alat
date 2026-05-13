@extends('layouts.app')

@section('title', 'Data Unit Operasional')

@php
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
            <h1 class="text-2xl font-bold text-gray-800"><i class="fa-solid fa-bolt text-pln-cyan mr-2"></i> Unit Operasional Pemakai</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola master data lokasi operasional (PLTA/PLTMH) tujuan peminjaman alat.</p>
        </div>
        <a href="{{ route('admin.unit-lokasi.create') }}" class="px-5 py-2.5 bg-pln-cyan text-white font-semibold rounded-xl shadow-md hover:bg-[#008Cca] transition flex items-center gap-2 border-2 border-[#008Cca]">
            <i class="fa-solid fa-plus"></i> Tambah Unit
        </a>
    </div>

    <div class="bg-white p-5 rounded-2xl shadow-sm border-2 border-gray-200">
        <form method="GET" action="{{ route('admin.unit-lokasi.index') }}" class="flex flex-col md:flex-row gap-4">
            <input type="hidden" name="sort_field" value="{{ $sortField }}">
            <input type="hidden" name="sort_direction" value="{{ $sortDirection }}">

            <div class="flex-1 relative">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-3.5 text-gray-400"></i>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama unit atau jenis (cth: PLTA)..." 
                    class="w-full pl-11 pr-4 py-2.5 rounded-xl border-2 border-gray-200 focus:border-pln-cyan focus:ring-0 text-sm transition-colors font-medium">
            </div>
            
            <button type="submit" class="px-6 py-2.5 bg-gray-800 text-white rounded-xl shadow-md hover:bg-gray-700 transition font-bold text-sm flex items-center justify-center gap-2 border-2 border-gray-800">
                <i class="fa-solid fa-filter"></i> Cari
            </button>
            @if($search)
                <a href="{{ route('admin.unit-lokasi.index') }}" class="px-6 py-2.5 bg-white text-red-600 rounded-xl hover:bg-red-50 transition font-bold text-sm border-2 border-red-200 flex items-center justify-center gap-2">
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
                        <th class="px-6 py-4 border-r border-white/20 w-16 text-center">No</th>
                        <th class="px-6 py-4 border-r border-white/20">
                            <a href="{{ sortUrl('nama_unit', $sortField, $sortDirection) }}" class="flex items-center hover:text-pln-yellow transition group">
                                NAMA UNIT OPERASIONAL {!! sortIcon('nama_unit', $sortField, $sortDirection) !!}
                            </a>
                        </th>
                        <th class="px-6 py-4 border-r border-white/20">
                            <a href="{{ sortUrl('jenis_unit', $sortField, $sortDirection) }}" class="flex items-center hover:text-pln-yellow transition group">
                                JENIS UNIT {!! sortIcon('jenis_unit', $sortField, $sortDirection) !!}
                            </a>
                        </th>
                        <th class="px-6 py-4 text-center w-32">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-sm">
                    @forelse($unit_lokasi as $index => $unit)
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        <td class="px-6 py-4 text-center text-gray-500 font-medium">
                            {{ $unit_lokasi->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4 font-bold text-gray-800 text-base">
                            <i class="fa-solid fa-building text-pln-cyan/50 mr-2"></i> {{ $unit->nama_unit }}
                        </td>
                        <td class="px-6 py-4">
                            @if($unit->jenis_unit)
                                <span class="px-3 py-1 bg-blue-50 text-blue-700 font-bold rounded-lg border-2 border-blue-200 text-xs">
                                    {{ $unit->jenis_unit }}
                                </span>
                            @else
                                <span class="text-gray-400 italic text-xs">Belum diatur</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.unit-lokasi.edit', $unit->id) }}" class="p-2 text-blue-600 bg-white rounded-lg hover:bg-blue-50 border-2 border-blue-200 transition" title="Edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <button type="button" onclick="confirmDelete({{ $unit->id }})" class="p-2 text-red-600 bg-white rounded-lg hover:bg-red-50 border-2 border-red-200 transition" title="Hapus">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                                <form id="delete-form-{{ $unit->id }}" action="{{ route('admin.unit-lokasi.destroy', $unit->id) }}" method="POST" class="hidden">
                                    @csrf @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-400 italic">
                            <i class="fa-solid fa-building-circle-xmark text-4xl mb-3 block opacity-50"></i>
                            Data unit operasional belum ada.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-5 border-t-2 border-gray-200 bg-gray-50/50">
            {{ $unit_lokasi->links() }}
        </div>
    </div>
</div>
@endsection