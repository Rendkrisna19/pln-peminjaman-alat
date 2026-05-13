@extends('layouts.app')

@section('title', 'Data Rak Penyimpanan')

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
            <h1 class="text-2xl font-bold text-gray-800"><i class="fa-solid fa-server text-pln-cyan mr-2"></i> Rak Penyimpanan</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola master data lokasi rak untuk penyimpanan inventaris alat.</p>
        </div>
        <a href="{{ route('admin.rak-penyimpanan.create') }}" class="px-5 py-2.5 bg-pln-cyan text-white font-semibold rounded-xl shadow-md hover:bg-[#008Cca] transition flex items-center gap-2 border-2 border-[#008Cca]">
            <i class="fa-solid fa-plus"></i> Tambah Rak
        </a>
    </div>

    <div class="bg-white p-5 rounded-2xl shadow-sm border-2 border-gray-200">
        <form method="GET" action="{{ route('admin.rak-penyimpanan.index') }}" class="flex flex-col md:flex-row gap-4">
            <input type="hidden" name="sort_field" value="{{ $sortField }}">
            <input type="hidden" name="sort_direction" value="{{ $sortDirection }}">

            <div class="flex-1 relative">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-3.5 text-gray-400"></i>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama rak atau lokasi..." 
                    class="w-full pl-11 pr-4 py-2.5 rounded-xl border-2 border-gray-200 focus:border-pln-cyan focus:ring-0 text-sm transition-colors font-medium">
            </div>
            
            <button type="submit" class="px-6 py-2.5 bg-gray-800 text-white rounded-xl shadow-md hover:bg-gray-700 transition font-bold text-sm flex items-center justify-center gap-2 border-2 border-gray-800">
                <i class="fa-solid fa-filter"></i> Cari
            </button>
            @if($search)
                <a href="{{ route('admin.rak-penyimpanan.index') }}" class="px-6 py-2.5 bg-white text-red-600 rounded-xl hover:bg-red-50 transition font-bold text-sm border-2 border-red-200 flex items-center justify-center gap-2">
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
                            <a href="{{ sortUrl('nama_rak', $sortField, $sortDirection) }}" class="flex items-center hover:text-pln-yellow transition group">
                                NAMA RAK {!! sortIcon('nama_rak', $sortField, $sortDirection) !!}
                            </a>
                        </th>
                        <th class="px-6 py-4 border-r border-white/20">
                            <a href="{{ sortUrl('lokasi_rak', $sortField, $sortDirection) }}" class="flex items-center hover:text-pln-yellow transition group">
                                DETAIL LOKASI {!! sortIcon('lokasi_rak', $sortField, $sortDirection) !!}
                            </a>
                        </th>
                        <th class="px-6 py-4 text-center w-32">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-sm">
                    @forelse($rak_penyimpanan as $index => $rak)
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        <td class="px-6 py-4 text-center text-gray-500 font-medium">
                            {{ $rak_penyimpanan->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4 font-bold text-gray-800 text-base">
                            <i class="fa-solid fa-box text-pln-cyan/50 mr-2"></i> {{ $rak->nama_rak }}
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            {{ $rak->lokasi_rak ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.rak-penyimpanan.edit', $rak->id) }}" class="p-2 text-blue-600 bg-white rounded-lg hover:bg-blue-50 border-2 border-blue-200 transition" title="Edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <button type="button" onclick="confirmDelete({{ $rak->id }})" class="p-2 text-red-600 bg-white rounded-lg hover:bg-red-50 border-2 border-red-200 transition" title="Hapus">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                                <form id="delete-form-{{ $rak->id }}" action="{{ route('admin.rak-penyimpanan.destroy', $rak->id) }}" method="POST" class="hidden">
                                    @csrf @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-400 italic">
                            <i class="fa-solid fa-server text-4xl mb-3 block opacity-50"></i>
                            Data rak penyimpanan belum ada.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-5 border-t-2 border-gray-200 bg-gray-50/50">
            {{ $rak_penyimpanan->links() }}
        </div>
    </div>
</div>
@endsection