@extends('layouts.app')

@section('title', 'Detail Peminjaman')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.peminjaman.index') }}" class="p-2 bg-white rounded-xl shadow-sm border border-gray-100 hover:bg-gray-50 text-gray-600 transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Detail Transaksi: <span class="text-pln-cyan">{{ $peminjaman->kode_peminjaman }}</span></h1>
    </div>

    <!-- BLOK ALERT NOTIFIKASI -->
    @if(session('success'))
        <div class="p-4 rounded-xl bg-green-50 border border-green-200 flex items-start gap-3">
            <svg class="w-5 h-5 text-green-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <div class="text-sm text-green-800 font-medium">{{ session('success') }}</div>
        </div>
    @endif

    @if(session('warning'))
        <div class="p-4 rounded-xl bg-yellow-50 border border-yellow-200 flex items-start gap-3">
            <svg class="w-5 h-5 text-yellow-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            <div class="text-sm text-yellow-800 font-medium">{{ session('warning') }}</div>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 rounded-xl bg-red-50 border border-red-200 flex items-start gap-3">
            <svg class="w-5 h-5 text-red-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <div class="text-sm text-red-800 font-medium">{{ session('error') }}</div>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-xl bg-red-50 border border-red-200 flex items-start gap-3">
            <svg class="w-5 h-5 text-red-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            <div class="text-sm text-red-800 font-medium">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
    <!-- END BLOK ALERT -->

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Sidebar Informasi -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">Informasi Peminjam</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-500 mb-1">Nama Pegawai</p>
                        <p class="font-semibold text-gray-800">{{ $peminjaman->user->nama_lengkap }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 mb-1">Unit Tujuan Operasional</p>
                        <p class="font-semibold text-gray-800">{{ $peminjaman->unit_tujuan->nama_unit }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 mb-1">Keterangan Pekerjaan</p>
                        <p class="font-medium text-gray-700 bg-gray-50 p-3 rounded-lg border border-gray-100">{{ $peminjaman->keterangan_pekerjaan }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">Jadwal & Status</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-500 mb-1">Tanggal Pengajuan</p>
                        <p class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($peminjaman->tanggal_pengajuan)->format('d M Y, H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 mb-1">Estimasi Pengembalian</p>
                        <p class="font-semibold text-red-600">{{ \Carbon\Carbon::parse($peminjaman->estimasi_kembali)->format('d M Y, H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 mb-2">Status Saat Ini</p>
                        @php
                            $statusColors = [
                                'Menunggu Verifikasi' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                'Sedang Dipinjam' => 'bg-blue-100 text-blue-800 border-blue-200',
                                'Dikembalikan' => 'bg-green-100 text-green-800 border-green-200',
                                'Ditolak' => 'bg-red-100 text-red-800 border-red-200',
                            ];
                            $colorClass = $statusColors[$peminjaman->status_peminjaman] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                        @endphp
                        <span class="inline-block px-4 py-1.5 rounded-full text-xs font-bold border {{ $colorClass }}">
                            {{ $peminjaman->status_peminjaman }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Area Konten Utama (Daftar Alat & Aksi) -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Tabel Daftar Alat -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="font-bold text-gray-800">Daftar Alat yang Dipinjam</h3>
                </div>
                
                @if($peminjaman->status_peminjaman == 'Sedang Dipinjam')
                <form action="{{ route('admin.peminjaman.pengembalian', $peminjaman->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin memproses pengembalian ini?')">
                    @csrf
                @endif

                <div class="overflow-x-auto p-5">
                    <table class="w-full text-left">
                        <thead class="text-xs uppercase text-gray-400 font-bold border-b border-gray-100">
                            <tr>
                                <th class="pb-3">Barcode Fisik</th>
                                <th class="pb-3">Nama Alat</th>
                                <th class="pb-3">Kondisi Keluar</th>
                                @if(in_array($peminjaman->status_peminjaman, ['Sedang Dipinjam', 'Dikembalikan']))
                                    <th class="pb-3 text-right">Kondisi Kembali</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($peminjaman->detail_peminjaman as $detail)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-4 font-mono text-sm text-pln-cyan font-semibold">{{ $detail->item_inventaris->kode_barcode }}</td>
                                <td class="py-4 text-sm text-gray-700">{{ $detail->item_inventaris->peralatan->nama_alat }}</td>
                                <td class="py-4 text-sm">
                                    <span class="px-2.5 py-1 bg-green-50 text-green-700 text-xs rounded-lg border border-green-100">
                                        {{ $detail->kondisi_saat_dipinjam }}
                                    </span>
                                </td>
                                
                                @if($peminjaman->status_peminjaman == 'Sedang Dipinjam')
                                    <td class="py-4 text-right">
                                        <div class="flex flex-col items-end gap-2">
                                            <select name="kondisi_kembali[{{ $detail->id }}]" class="text-sm rounded-lg border-gray-300 focus:ring-pln-cyan focus:border-pln-cyan w-32" required>
                                                <option value="" disabled selected>Pilih...</option>
                                                <option value="Baik">Baik</option>
                                                <option value="Rusak">Rusak</option>
                                                <option value="Hilang">Hilang</option>
                                            </select>
                                            <input type="text" name="catatan_kerusakan[{{ $detail->id }}]" placeholder="Catatan (Opsional)..." class="text-xs rounded-lg border-gray-200 w-full sm:w-48 focus:ring-pln-cyan">
                                        </div>
                                    </td>
                                @elseif($peminjaman->status_peminjaman == 'Dikembalikan')
                                    <td class="py-4 text-right text-sm">
                                        <span class="px-2.5 py-1 text-xs rounded-lg border font-semibold {{ $detail->kondisi_saat_kembali == 'Baik' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-red-50 text-red-700 border-red-200' }}">
                                            {{ $detail->kondisi_saat_kembali }}
                                        </span>
                                        @if($detail->catatan_kerusakan)
                                            <p class="text-xs text-gray-500 mt-2 italic bg-gray-50 p-2 rounded border border-gray-100">"{{ $detail->catatan_kerusakan }}"</p>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($peminjaman->status_peminjaman == 'Sedang Dipinjam')
                    <div class="p-5 bg-yellow-50/50 border-t border-yellow-100 flex flex-col sm:flex-row justify-between items-center gap-4">
                        <p class="text-sm text-yellow-800">Pastikan Anda telah mengecek fisik alat secara langsung sebelum memproses pengembalian.</p>
                        <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-pln-cyan hover:bg-[#008Cca] text-white font-bold rounded-xl shadow-lg shadow-pln-cyan/30 transition-all">
                            Proses Pengembalian Alat
                        </button>
                    </div>
                </form>
                @endif
            </div>

            <!-- Panel Verifikasi -->
            @if($peminjaman->status_peminjaman == 'Menunggu Verifikasi')
            <div class="bg-white p-6 rounded-2xl shadow-sm border-2 border-pln-cyan/20 flex flex-col sm:flex-row gap-6 items-center justify-between relative overflow-hidden">
                <!-- Dekorasi -->
                <div class="absolute top-0 right-0 w-32 h-32 bg-pln-cyan/5 rounded-full -mr-16 -mt-16 pointer-events-none"></div>

                <div class="relative z-10 w-full sm:w-auto text-center sm:text-left">
                    <h3 class="font-bold text-gray-800 text-lg">Verifikasi Permohonan</h3>
                    <p class="text-sm text-gray-500 mt-1">Tinjau stok alat dan tujuan operasional sebelum memberikan persetujuan.</p>
                </div>
                
                <div class="flex w-full sm:w-auto gap-3 relative z-10">
                    <form action="{{ route('admin.peminjaman.verifikasi', $peminjaman->id) }}" method="POST" class="flex-1 sm:flex-none">
                        @csrf
                        <input type="hidden" name="status_peminjaman" value="Ditolak">
                        <button type="submit" class="w-full px-6 py-3 bg-white border-2 border-red-500 text-red-500 hover:bg-red-50 font-bold rounded-xl transition-all focus:ring-4 focus:ring-red-100" onclick="return confirm('Apakah Anda yakin ingin menolak permohonan ini?')">
                            Tolak
                        </button>
                    </form>

                    <form action="{{ route('admin.peminjaman.verifikasi', $peminjaman->id) }}" method="POST" class="flex-1 sm:flex-none">
                        @csrf
                        <input type="hidden" name="status_peminjaman" value="Disetujui">
                        <button type="submit" class="w-full px-6 py-3 bg-pln-cyan hover:bg-[#008Cca] text-white font-bold rounded-xl shadow-lg shadow-pln-cyan/30 transition-all focus:ring-4 focus:ring-pln-cyan/20" onclick="return confirm('Apakah Anda yakin ingin menyetujui dan mengeluarkan alat ini?')">
                            Setujui & Keluarkan
                        </button>
                    </form>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection