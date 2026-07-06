@extends('layouts.app')

@section('title', 'Tracking Log Alat')

@section('content')
<div class="w-full space-y-6 pb-10">
    
    <!-- Header Area (Tetap sama seperti sebelumnya) -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-blue-50 text-pln-cyan rounded-2xl flex items-center justify-center text-2xl border-2 border-blue-100 shadow-inner">
                <i class="fa-solid fa-route"></i>
            </div>
            <div>
                <h1 class="text-2xl font-extrabold text-gray-800 tracking-tight">Tracking Log Peralatan</h1>
                <p class="text-sm text-gray-500 font-medium mt-0.5">Pantau seluruh aktivitas, perpindahan, dan penggunaan aset secara real-time.</p>
            </div>
        </div>
    </div>

    <!-- Area Filter & Pencarian (DIPERBARUI) -->
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200">
        <form method="GET" action="{{ route('admin.tracking.index') }}" class="flex flex-col xl:flex-row gap-4">
            
            <!-- Pencarian Teks -->
            <div class="relative flex-1">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-3.5 text-gray-400"></i>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari Kode Barang atau Nama Alat..." 
                    class="w-full pl-11 pr-4 py-3 rounded-xl border-2 border-gray-100 focus:border-pln-cyan focus:ring-0 text-sm font-bold text-gray-700 shadow-sm transition hover:border-gray-200">
            </div>

            <div class="flex flex-wrap sm:flex-row gap-4">
                
                <!-- Pilihan Jumlah Data Per Halaman -->
                <div class="relative w-full sm:w-32">
                    <label class="absolute -top-2.5 left-3 bg-white px-1 text-[10px] font-extrabold text-gray-400 uppercase tracking-wider">Tampilkan</label>
                    <select name="per_page" onchange="this.form.submit()" class="w-full pl-4 pr-10 py-3 rounded-xl border-2 border-gray-100 focus:border-pln-cyan focus:ring-0 text-sm font-bold text-gray-700 appearance-none bg-white shadow-sm cursor-pointer transition hover:border-gray-200">
                        <option value="10" {{ ($perPage ?? 10) == 10 ? 'selected' : '' }}>10 Baris</option>
                        <option value="15" {{ ($perPage ?? 15) == 15 ? 'selected' : '' }}>15 Baris</option>
                        <option value="25" {{ ($perPage ?? 25) == 25 ? 'selected' : '' }}>25 Baris</option>
                        <option value="50" {{ ($perPage ?? 50) == 50 ? 'selected' : '' }}>50 Baris</option>
                        <option value="100" {{ ($perPage ?? 100) == 100 ? 'selected' : '' }}>100 Baris</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                        <i class="fa-solid fa-chevron-down text-xs"></i>
                    </div>
                </div>

                <!-- Filter Harian -->
                <div class="relative w-full sm:w-40">
                    <label class="absolute -top-2.5 left-3 bg-white px-1 text-[10px] font-extrabold text-gray-400 uppercase tracking-wider">Tgl Spesifik</label>
                    <input type="date" name="filter_date" value="{{ $filterDate }}" 
                        class="w-full px-4 py-3 rounded-xl border-2 border-gray-100 focus:border-pln-cyan focus:ring-0 text-sm font-bold text-gray-700 shadow-sm transition hover:border-gray-200 cursor-pointer">
                </div>

                <!-- Filter Bulanan -->
                <div class="relative w-full sm:w-44">
                    <label class="absolute -top-2.5 left-3 bg-white px-1 text-[10px] font-extrabold text-gray-400 uppercase tracking-wider">Atau Bulan</label>
                    <input type="month" name="filter_month" value="{{ $filterMonth }}" 
                        class="w-full px-4 py-3 rounded-xl border-2 border-gray-100 focus:border-pln-cyan focus:ring-0 text-sm font-bold text-gray-700 shadow-sm transition hover:border-gray-200 cursor-pointer">
                </div>

                <!-- Sort Direction -->
                <div class="relative w-full sm:w-48">
                    <i class="fa-solid fa-sort absolute left-4 top-3.5 text-gray-400"></i>
                    <select name="sort_direction" class="w-full pl-10 pr-8 py-3 rounded-xl border-2 border-gray-100 focus:border-pln-cyan focus:ring-0 text-sm font-bold text-gray-700 appearance-none bg-white shadow-sm cursor-pointer transition hover:border-gray-200">
                        <option value="desc" {{ $sortDirection == 'desc' ? 'selected' : '' }}>Terbaru ke Terlama</option>
                        <option value="asc" {{ $sortDirection == 'asc' ? 'selected' : '' }}>Terlama ke Terbaru</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                        <i class="fa-solid fa-chevron-down text-xs"></i>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-2 w-full xl:w-auto">
                <button type="submit" class="flex-1 xl:flex-none px-8 py-3 bg-pln-dark text-white font-extrabold rounded-xl border-2 border-pln-dark hover:bg-gray-800 transition shadow-md flex items-center justify-center gap-2">
                    <i class="fa-solid fa-filter text-sm"></i> Terapkan
                </button>
                @if($search || $filterDate || $filterMonth)
                    <a href="{{ route('admin.tracking.index') }}" class="px-4 py-3 bg-gray-50 text-red-500 font-bold rounded-xl border-2 border-gray-200 hover:bg-red-50 hover:border-red-200 hover:text-red-600 transition shadow-sm flex items-center justify-center" title="Reset Filter">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                @endif
            </div>
        </form>

        <!-- Export Buttons -->
        <div class="mt-5 pt-5 border-t-2 border-gray-100 flex flex-wrap gap-3">
            <a target="_blank" href="{{ route('admin.tracking.pdf', request()->query()) }}" class="px-6 py-3 bg-red-600 text-white font-bold rounded-xl border-2 border-red-700 hover:bg-red-700 transition flex items-center gap-2 shadow-md">
                <i class="fa-solid fa-file-pdf"></i> Download PDF
            </a>
            <a href="{{ route('admin.tracking.excel', request()->query()) }}" class="px-6 py-3 bg-[#107C41] text-white font-bold rounded-xl border-2 border-[#0e6b38] hover:bg-[#0e6b38] transition flex items-center gap-2 shadow-md">
                <i class="fa-solid fa-file-excel"></i> Download Excel
            </a>
        </div>
    </div>

    <!-- Tabel Data Logging -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <!-- ... SAMA SEPERTI KODE SEBELUMNYA ... -->
                <thead class="bg-pln-cyan text-white text-xs uppercase font-extrabold tracking-wider border-b-2 border-blue-600">
                    <tr>
                        <th class="px-6 py-4">Waktu</th>
                        <th class="px-6 py-4">Fisik (Kode Barang) & Alat</th>
                        <th class="px-6 py-4">Aktivitas & Lokasi</th>
                        <th class="px-6 py-4">Pelaku / Penanggung Jawab</th>
                        <th class="px-6 py-4 text-center">Jejak Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($tracking_logs as $log)
                    <tr class="hover:bg-blue-50/40 transition-colors group">
                        
                        <!-- Waktu -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-bold text-gray-800">{{ \Carbon\Carbon::parse($log->tanggal_waktu)->format('d M Y') }}</span>
                            <span class="block text-xs text-gray-500 font-medium mt-0.5"><i class="fa-regular fa-clock"></i> {{ \Carbon\Carbon::parse($log->tanggal_waktu)->format('H:i:s') }} WIB</span>
                        </td>
                        
                        <!-- Fisik Alat -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2 mb-1">
                                <i class="fa-solid fa-hashtag text-gray-400 text-xs"></i>
                                <span class="font-mono text-sm text-pln-cyan font-extrabold bg-blue-50 px-2 py-0.5 rounded border border-blue-100">
                                    {{ $log->item_inventaris->kode_barcode ?? 'Dihapus' }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-600 font-bold truncate max-w-[250px]" title="{{ $log->item_inventaris->peralatan->nama_alat ?? '-' }}">
                                {{ $log->item_inventaris->peralatan->nama_alat ?? '-' }}
                            </p>
                        </td>

                        <!-- Aktivitas & Lokasi -->
                        <td class="px-6 py-4">
                            <span class="inline-block px-3 py-1 bg-gray-100 text-gray-700 text-xs font-bold rounded-lg border border-gray-200 shadow-sm mb-1">
                                {{ $log->aktivitas }}
                            </span>
                            @if($log->unit_lokasi)
                                <p class="text-xs text-blue-600 mt-1 font-extrabold flex items-center gap-1.5">
                                    <i class="fa-solid fa-location-dot"></i> {{ $log->unit_lokasi->nama_unit }}
                                </p>
                            @else
                                <p class="text-[10px] text-gray-400 mt-1 font-bold italic uppercase"><i class="fa-solid fa-warehouse"></i> Gudang Utama</p>
                            @endif
                        </td>

                        <!-- Pelaku -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-gray-100 text-gray-500 flex items-center justify-center text-xs font-bold border border-gray-200">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                                <span class="text-sm font-bold text-gray-700">
                                    {{ $log->user->name ?? $log->user->nama_lengkap ?? 'Sistem / Anonim' }}
                                </span>
                            </div>
                        </td>

                        <!-- Aksi Detail -->
                        <td class="px-6 py-4 text-center">
                            @if($log->item_inventaris_id)
                                <a href="{{ route('admin.tracking.history', $log->item_inventaris_id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white text-pln-cyan border-2 border-pln-cyan hover:bg-pln-cyan hover:text-white rounded-xl text-xs font-bold shadow-sm transition-all duration-300">
                                    <i class="fa-solid fa-shoe-prints"></i> Lacak Alat
                                </a>
                            @else
                                <span class="text-xs text-gray-400 italic">Data Induk Dihapus</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-20 h-20 bg-gray-50 border-2 border-dashed border-gray-200 rounded-full flex items-center justify-center mb-4">
                                    <i class="fa-solid fa-satellite-dish text-3xl text-gray-300"></i>
                                </div>
                                <h3 class="text-lg font-bold text-gray-700">Tidak ada log aktivitas</h3>
                                <p class="text-sm text-gray-500 mt-1">Belum ada jejak riwayat alat yang tercatat atau sesuai dengan filter.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Info & Pagination -->
        <div class="flex flex-col sm:flex-row justify-between items-center px-6 py-4 border-t border-gray-100 bg-gray-50/50 gap-4">
            <p class="text-sm font-bold text-gray-500">
                Menampilkan <span class="text-pln-cyan">{{ $tracking_logs->count() }}</span> dari <span class="text-pln-cyan">{{ $tracking_logs->total() }}</span> log aktivitas.
            </p>
            <div class="w-full sm:w-auto overflow-x-auto">
                {{ $tracking_logs->links() }}
            </div>
        </div>
    </div>
</div>
@endsection