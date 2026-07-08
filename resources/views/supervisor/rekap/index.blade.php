@extends('layouts.app')
@section('title', 'Rekap Laporan')

@section('content')
<div class="w-full space-y-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800"><i class="fa-solid fa-file-invoice text-pln-cyan mr-2"></i> Rekapitulasi Laporan</h1>
            <p class="text-sm text-gray-500 mt-1">Unduh Laporan Format PDF & Excel Standar Industri.</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-3xl border-2 border-gray-200 shadow-sm">
        <form action="{{ route('supervisor.rekap.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="w-full px-4 py-2.5 rounded-xl border-2 border-gray-200 focus:border-pln-cyan focus:ring-0 text-sm font-bold">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="w-full px-4 py-2.5 rounded-xl border-2 border-gray-200 focus:border-pln-cyan focus:ring-0 text-sm font-bold">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Status Peminjaman</label>
                <select name="status" class="w-full px-4 py-2.5 rounded-xl border-2 border-gray-200 focus:border-pln-cyan focus:ring-0 text-sm font-bold">
                    <option value="">Semua Status</option>
                    <option value="Menunggu Verifikasi" {{ $status == 'Menunggu Verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                    <option value="Sedang Dipinjam" {{ $status == 'Sedang Dipinjam' ? 'selected' : '' }}>Sedang Dipinjam</option>
                    <option value="Dikembalikan" {{ $status == 'Dikembalikan' ? 'selected' : '' }}>Dikembalikan (Selesai)</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 py-2.5 bg-pln-dark text-white rounded-xl font-bold text-sm border-2 border-pln-dark hover:bg-gray-800 transition">
                    <i class="fa-solid fa-filter"></i> Filter
                </button>
                <a href="{{ route('supervisor.rekap.index') }}" class="p-2.5 bg-gray-100 text-gray-500 rounded-xl border-2 border-gray-200 hover:bg-gray-200 transition">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
            </div>
        </form>

        <div class="mt-6 pt-6 border-t-2 border-gray-100 flex flex-wrap gap-3">
            <a target="_blank" href="{{ route('supervisor.rekap.pdf', request()->query()) }}" class="px-6 py-3 bg-red-600 text-white font-bold rounded-xl border-2 border-red-700 hover:bg-red-700 transition flex items-center gap-2 shadow-md">
                <i class="fa-solid fa-file-pdf"></i> Download PDF
            </a>
            <a href="{{ route('supervisor.rekap.excel', request()->query()) }}" class="px-6 py-3 bg-[#107C41] text-white font-bold rounded-xl border-2 border-[#0e6b38] hover:bg-[#0e6b38] transition flex items-center gap-2 shadow-md">
                <i class="fa-solid fa-file-excel"></i> Download Excel
            </a>
        </div>
    </div>



    <div class="bg-white rounded-3xl border-2 border-gray-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-pln-cyan text-white text-xs uppercase font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-4 border-r border-white/20">TRX & Tanggal</th>
                        <th class="px-6 py-4 border-r border-white/20">Pegawai</th>
                        <th class="px-6 py-4 border-r border-white/20">Lokasi Kerja</th>
                        <th class="px-6 py-4 border-r border-white/20">Rincian Alat</th>
                        <th class="px-6 py-4 border-r border-white/20 text-center">Jml Dipinjam</th>
                        <th class="px-6 py-4 border-r border-white/20 text-center">Sisa Stok Alat</th>
                        <th class="px-6 py-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y-2 divide-gray-100 text-sm">
                    @forelse($rekap as $row)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <span class="font-bold text-gray-800">{{ $row->kode_peminjaman }}</span>
                            <span class="text-[10px] text-gray-500 block mt-1"><i class="fa-regular fa-calendar mr-1"></i>{{ \Carbon\Carbon::parse($row->tanggal_pengajuan)->format('d/m/Y') }}</span>
                        </td>
                        <td class="px-6 py-4 font-bold text-gray-800">{{ $row->user->nama_lengkap }}</td>
                        <td class="px-6 py-4 text-gray-600 font-medium">{{ $row->unit_tujuan->nama_unit ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <ul class="list-disc list-inside text-xs font-bold text-pln-cyan">
                                @foreach($row->detail_peminjaman->take(2) as $det)
                                    <li class="truncate max-w-[200px]">{{ $det->item_inventaris->peralatan->nama_alat }}</li>
                                @endforeach
                            </ul>
                            @if($row->detail_peminjaman->count() > 2)
                                <span class="text-[10px] text-gray-400 font-bold mt-1 block">Dan {{ $row->detail_peminjaman->count() - 2 }} lainnya...</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center font-bold text-gray-800">
                            {{ $row->detail_peminjaman->count() }} Unit
                        </td>
                        <td class="px-6 py-4 text-center">
                            <ul class="list-none text-xs font-bold text-green-600 space-y-1">
                                @foreach($row->detail_peminjaman->take(2) as $det)
                                    <li>{{ \App\Models\ItemInventaris::where('peralatan_id', $det->item_inventaris->peralatan_id)->where('status_ketersediaan', 'Tersedia')->count() }} Unit</li>
                                @endforeach
                            </ul>
                            @if($row->detail_peminjaman->count() > 2)
                                <span class="text-[10px] text-gray-400 font-bold mt-1 block">...</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-lg text-[10px] font-black uppercase border border-gray-200">{{ $row->status_peminjaman }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400 italic">Filter data tidak menemukan hasil.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-5 border-t-2 border-gray-100">
            {{ $rekap->links() }}
        </div>
    </div>
</div>
@endsection