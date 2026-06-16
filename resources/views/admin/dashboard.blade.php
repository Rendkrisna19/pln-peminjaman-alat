@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="w-full space-y-8 pb-10">
    
    <div class="relative w-full rounded-3xl overflow-hidden shadow-sm border border-gray-100 group min-h-[320px] md:min-h-[400px] flex items-center bg-gray-900">
        <img src="{{ asset('images/banner1.png') }}" alt="Banner Admin" class="absolute inset-0 w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-1000 ease-in-out opacity-80">
        
        <div class="absolute inset-0 bg-gradient-to-r from-gray-900/95 via-gray-900/70 to-transparent"></div>
        
        <div class="relative z-10 p-6 sm:p-10 w-full md:w-3/4 lg:w-2/3">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-white/10 backdrop-blur-md rounded-full border border-white/20 w-max mb-4 shadow-sm">
                <span class="w-2.5 h-2.5 rounded-full bg-green-400 animate-pulse shadow-[0_0_8px_rgba(74,222,128,0.8)]"></span>
                <span class="text-[10px] sm:text-xs font-bold text-white tracking-widest uppercase">Sistem Berjalan Normal</span>
            </div>

            <h1 class="text-3xl sm:text-4xl md:text-5xl font-black text-white mb-3 drop-shadow-lg leading-tight">
                Dashboard <span class="text-pln-cyan">Administrator</span>
            </h1>
            
            <p class="text-gray-300 font-medium text-sm sm:text-base leading-relaxed drop-shadow max-w-2xl mb-8">
                Pantau ketersediaan, status fisik peralatan kerja hari ini, serta kelola seluruh aktivitas inventaris dan peminjaman dalam satu kendali terpusat.
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-6">
        
        {{-- Card 1: Total Aset (Unit Fisik) --}}
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 hover:bg-pln-cyan hover:shadow-xl hover:-translate-y-1 transition-all duration-500 ease-in-out group cursor-pointer">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-pln-cyan/10 group-hover:bg-white/20 rounded-2xl transition-colors duration-500">
                    <svg class="w-6 h-6 text-pln-cyan group-hover:text-white transition-colors duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <span class="text-xs font-bold text-gray-400 group-hover:text-blue-100 uppercase tracking-wider transition-colors duration-500">Total Aset</span>
            </div>
            <h3 class="text-3xl font-black text-gray-800 group-hover:text-white transition-colors duration-500">{{ $total_alat }}</h3>
            <p class="text-sm text-gray-500 group-hover:text-blue-100 mt-1 transition-colors duration-500">Unit fisik terdaftar</p>
        </div>

        {{-- Card 2: Tersedia --}}
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 hover:bg-gray-600 hover:shadow-xl hover:-translate-y-1 transition-all duration-500 ease-in-out group cursor-pointer">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-gray-100 group-hover:bg-white/20 rounded-2xl transition-colors duration-500">
                    <svg class="w-6 h-6 text-gray-600 group-hover:text-white transition-colors duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <span class="text-xs font-bold text-gray-400 group-hover:text-gray-300 uppercase tracking-wider transition-colors duration-500">Tersedia</span>
            </div>
            <h3 class="text-3xl font-black text-gray-800 group-hover:text-white transition-colors duration-500">{{ $alat_tersedia }}</h3>
            <p class="text-sm text-gray-500 group-hover:text-gray-300 mt-1 transition-colors duration-500">Siap dipinjam</p>
        </div>

        {{-- Card 3: Dipinjam --}}
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 hover:bg-[#e5c100] hover:shadow-xl hover:-translate-y-1 transition-all duration-500 ease-in-out group cursor-pointer">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-yellow-50 group-hover:bg-white/30 rounded-2xl transition-colors duration-500">
                    <svg class="w-6 h-6 text-[#e5c100] group-hover:text-white transition-colors duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <span class="text-xs font-bold text-gray-400 group-hover:text-yellow-900 uppercase tracking-wider transition-colors duration-500">Dipinjam</span>
            </div>
            <h3 class="text-3xl font-black text-gray-800 group-hover:text-white transition-colors duration-500">{{ $alat_dipinjam }}</h3>
            <p class="text-sm text-gray-500 group-hover:text-yellow-50 mt-1 transition-colors duration-500">Di teknisi lapangan</p>
        </div>

        {{-- Card 4: Perbaikan (Rusak Ringan + Rusak Berat) --}}
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 hover:bg-red-500 hover:shadow-xl hover:-translate-y-1 transition-all duration-500 ease-in-out group cursor-pointer">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-red-50 group-hover:bg-white/20 rounded-2xl transition-colors duration-500">
                    <svg class="w-6 h-6 text-red-500 group-hover:text-white transition-colors duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <span class="text-xs font-bold text-gray-400 group-hover:text-red-100 uppercase tracking-wider transition-colors duration-500">Perbaikan</span>
            </div>
            <h3 class="text-3xl font-black text-gray-800 group-hover:text-white transition-colors duration-500">{{ $alat_rusak }}</h3>
            <p class="text-sm text-gray-500 group-hover:text-red-100 mt-1 transition-colors duration-500">Butuh maintenance</p>
        </div>

        {{-- Card 5: Menunggu Verifikasi --}}
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 hover:bg-blue-600 hover:shadow-xl hover:-translate-y-1 transition-all duration-500 ease-in-out group cursor-pointer">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-blue-50 group-hover:bg-white/20 rounded-2xl transition-colors duration-500">
                    <svg class="w-6 h-6 text-blue-500 group-hover:text-white transition-colors duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                </div>
                <span class="text-xs font-bold text-gray-400 group-hover:text-blue-100 uppercase tracking-wider transition-colors duration-500">Pending</span>
            </div>
            <h3 class="text-3xl font-black text-gray-800 group-hover:text-white transition-colors duration-500">{{ $pending_peminjaman }}</h3>
            <p class="text-sm text-gray-500 group-hover:text-blue-100 mt-1 transition-colors duration-500">Menunggu verifikasi</p>
        </div>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-50 flex items-center justify-between">
                <h3 class="font-bold text-gray-800">Aktivitas Peminjaman Terbaru</h3>
                <a href="{{ route('admin.peminjaman.index') }}" class="text-pln-cyan text-xs font-bold hover:underline">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50/50 text-gray-400 text-xs uppercase font-bold">
                        <tr>
                            <th class="px-6 py-4">Peminjam</th>
                            <th class="px-6 py-4">Unit Tujuan</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($recent_activities as $activity)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-pln-cyan text-white flex items-center justify-center text-xs font-bold">
                                        {{ substr($activity->user->nama_lengkap, 0, 1) }}
                                    </div>
                                    <span class="text-sm font-semibold text-gray-700">{{ $activity->user->nama_lengkap }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $activity->unit_tujuan->nama_unit }}</td>
                            <td class="px-6 py-4">
                                @if($activity->status_peminjaman == 'Menunggu Verifikasi')
                                    <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-xs font-bold">Menunggu</span>
                                @elseif($activity->status_peminjaman == 'Sedang Dipinjam')
                                    <span class="px-3 py-1 bg-yellow-50 text-yellow-600 rounded-full text-xs font-bold">Dipinjam</span>
                                @else
                                    <span class="px-3 py-1 bg-gray-50 text-gray-600 rounded-full text-xs font-bold">{{ $activity->status_peminjaman }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.peminjaman.show', $activity->id) }}" class="p-2 hover:bg-white rounded-xl transition-all inline-block border border-transparent hover:border-gray-200">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-gray-400 text-sm italic">Belum ada aktivitas peminjaman.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-pln-dark rounded-3xl p-6 text-white shadow-xl relative overflow-hidden">
                <div class="relative z-10">
                    <h3 class="font-bold text-lg mb-2 text-pln-yellow">Pintasan Cepat</h3>
                    <p class="text-xs text-gray-400 mb-6">Kelola master data dengan satu klik.</p>
                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('admin.peralatan.create') }}" class="p-4 bg-white/10 hover:bg-white/20 rounded-2xl text-center backdrop-blur-sm transition-all border border-white/10">
                            <span class="block text-xs font-bold uppercase tracking-widest">+ Alat</span>
                        </a>
                        <a href="{{ route('admin.users.create') }}" class="p-4 bg-white/10 hover:bg-white/20 rounded-2xl text-center backdrop-blur-sm transition-all border border-white/10">
                            <span class="block text-xs font-bold uppercase tracking-widest">+ User</span>
                        </a>
                    </div>
                </div>
                <div class="absolute -bottom-6 -right-6 w-32 h-32 bg-pln-cyan opacity-20 rounded-full blur-2xl"></div>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <h4 class="font-bold text-gray-800 mb-4">Ringkasan Sistem</h4>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-2xl">
                        <span class="text-xs text-gray-500 font-medium">Total Rak Tersedia</span>
                        <span class="text-sm font-bold text-gray-800">{{ \App\Models\RakPenyimpanan::count() }} Rak</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-2xl">
                        <span class="text-xs text-gray-500 font-medium">Unit Operasional</span>
                        <span class="text-sm font-bold text-gray-800">{{ \App\Models\UnitLokasi::count() }} Lokasi</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-blue-50 rounded-2xl">
                        <span class="text-xs text-blue-600 font-medium">Total Peminjaman</span>
                        <span class="text-sm font-bold text-blue-700">{{ $total_peminjaman }} Transaksi</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-pln-cyan/10 rounded-2xl">
                        <span class="text-xs text-pln-cyan font-medium">Katalog Alat</span>
                        <span class="text-sm font-bold text-gray-800">{{ $total_peralatan }} Jenis</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-2xl">
                        <span class="text-xs text-gray-500 font-medium">Total Pegawai</span>
                        <span class="text-sm font-bold text-gray-800">{{ $total_user }} Orang</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection