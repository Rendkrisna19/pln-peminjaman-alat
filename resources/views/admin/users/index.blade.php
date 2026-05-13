@extends('layouts.app')

@section('title', 'Kelola Pengguna')

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
            <h1 class="text-2xl font-bold text-gray-800"><i class="fa-solid fa-users-gear text-pln-cyan mr-2"></i> Kelola Pengguna</h1>
            <p class="text-sm text-gray-500 mt-1">Manajemen akun hak akses Admin, Pegawai (Teknisi), dan Supervisor.</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="px-5 py-2.5 bg-pln-cyan text-white font-semibold rounded-xl shadow-md hover:bg-[#008Cca] transition flex items-center gap-2 border-2 border-[#008Cca]">
            <i class="fa-solid fa-user-plus"></i> Tambah Pengguna
        </a>
    </div>

    <div class="bg-white p-5 rounded-2xl shadow-sm border-2 border-gray-200">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col md:flex-row gap-4">
            <input type="hidden" name="sort_field" value="{{ $sortField }}">
            <input type="hidden" name="sort_direction" value="{{ $sortDirection }}">

            <div class="flex-1 relative">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-3.5 text-gray-400"></i>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama lengkap atau email..." 
                    class="w-full pl-11 pr-4 py-2.5 rounded-xl border-2 border-gray-200 focus:border-pln-cyan focus:ring-0 text-sm transition-colors font-medium">
            </div>
            <div class="md:w-64">
                <select name="role" class="w-full px-4 py-2.5 rounded-xl border-2 border-gray-200 focus:border-pln-cyan focus:ring-0 text-sm transition-colors font-medium">
                    <option value="">Semua Hak Akses (Role)</option>
                    <option value="admin" {{ $filterRole == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="pegawai" {{ $filterRole == 'pegawai' ? 'selected' : '' }}>Pegawai (Teknisi)</option>
                    <option value="supervisor" {{ $filterRole == 'supervisor' ? 'selected' : '' }}>Supervisor</option>
                </select>
            </div>
            <button type="submit" class="px-6 py-2.5 bg-gray-800 text-white rounded-xl shadow-md hover:bg-gray-700 transition font-bold text-sm flex items-center justify-center gap-2 border-2 border-gray-800">
                <i class="fa-solid fa-filter"></i> Filter
            </button>
            @if($search || $filterRole)
                <a href="{{ route('admin.users.index') }}" class="px-6 py-2.5 bg-white text-red-600 rounded-xl hover:bg-red-50 transition font-bold text-sm border-2 border-red-200 flex items-center justify-center gap-2">
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
                        <th class="px-6 py-4 border-r border-white/20 w-16 text-center">NO</th>
                        <th class="px-6 py-4 border-r border-white/20">
                            <a href="{{ sortUrl('nama_lengkap', $sortField, $sortDirection) }}" class="flex items-center hover:text-pln-yellow transition group">
                                NAMA LENGKAP {!! sortIcon('nama_lengkap', $sortField, $sortDirection) !!}
                            </a>
                        </th>
                        <th class="px-6 py-4 border-r border-white/20">
                            <a href="{{ sortUrl('email', $sortField, $sortDirection) }}" class="flex items-center hover:text-pln-yellow transition group">
                                EMAIL {!! sortIcon('email', $sortField, $sortDirection) !!}
                            </a>
                        </th>
                        <th class="px-6 py-4 border-r border-white/20 text-center">
                            <a href="{{ sortUrl('role', $sortField, $sortDirection) }}" class="flex items-center justify-center hover:text-pln-yellow transition group">
                                HAK AKSES {!! sortIcon('role', $sortField, $sortDirection) !!}
                            </a>
                        </th>
                        <th class="px-6 py-4 text-center w-32">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-sm">
                    @forelse($users as $index => $user)
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        <td class="px-6 py-4 text-center text-gray-500 font-medium">
                            {{ $users->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4 font-bold text-gray-800 text-base">
                            <i class="fa-solid fa-circle-user text-gray-400 mr-2 text-lg"></i> {{ $user->nama_lengkap }}
                        </td>
                        <td class="px-6 py-4 text-gray-600 font-medium">
                            {{ $user->email }}
                            <div class="text-xs text-gray-400 mt-1"><i class="fa-solid fa-phone mr-1"></i> {{ $user->no_telepon ?? 'Tidak ada No HP' }}</div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($user->role == 'admin')
                                <span class="px-3 py-1 bg-red-100 text-red-700 font-bold rounded-lg border-2 border-red-200 text-xs"><i class="fa-solid fa-shield-halved mr-1"></i> Admin</span>
                            @elseif($user->role == 'supervisor')
                                <span class="px-3 py-1 bg-purple-100 text-purple-700 font-bold rounded-lg border-2 border-purple-200 text-xs"><i class="fa-solid fa-eye mr-1"></i> Supervisor</span>
                            @else
                                <span class="px-3 py-1 bg-blue-100 text-blue-700 font-bold rounded-lg border-2 border-blue-200 text-xs"><i class="fa-solid fa-user-gear mr-1"></i> Pegawai</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="p-2 text-blue-600 bg-white rounded-lg hover:bg-blue-50 border-2 border-blue-200 transition" title="Edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                @if($user->id !== Auth::id())
                                <button type="button" onclick="confirmDelete({{ $user->id }})" class="p-2 text-red-600 bg-white rounded-lg hover:bg-red-50 border-2 border-red-200 transition" title="Hapus">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                                <form id="delete-form-{{ $user->id }}" action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="hidden">
                                    @csrf @method('DELETE')
                                </form>
                                @else
                                <button type="button" class="p-2 text-gray-400 bg-gray-100 rounded-lg border-2 border-gray-200 cursor-not-allowed" title="Anda sedang login dengan akun ini">
                                    <i class="fa-solid fa-ban"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400 italic">
                            <i class="fa-solid fa-users-slash text-4xl mb-3 block opacity-50"></i>
                            Data pengguna tidak ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-5 border-t-2 border-gray-200 bg-gray-50/50">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection