@extends('layouts.app')

@section('title', 'Detail Peminjaman')

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.peminjaman.index') }}" class="p-2 bg-white rounded-xl shadow-sm border border-gray-100 hover:bg-gray-50 text-gray-600 transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Detail Transaksi: <span class="text-pln-cyan">{{ $peminjaman->kode_peminjaman }}</span></h1>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-green-50 border border-green-200 flex items-start gap-3 shadow-sm">
            <svg class="w-5 h-5 text-green-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <div class="text-sm text-green-800 font-bold">{{ session('success') }}</div>
        </div>
    @endif

    @if(session('warning'))
        <div class="p-4 rounded-xl bg-yellow-50 border border-yellow-200 flex items-start gap-3 shadow-sm">
            <svg class="w-5 h-5 text-yellow-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            <div class="text-sm text-yellow-800 font-bold">{{ session('warning') }}</div>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 rounded-xl bg-red-50 border border-red-200 flex items-start gap-3 shadow-sm">
            <svg class="w-5 h-5 text-red-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <div class="text-sm text-red-800 font-bold">{{ session('error') }}</div>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-xl bg-red-50 border border-red-200 flex items-start gap-3 shadow-sm">
            <svg class="w-5 h-5 text-red-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            <div class="text-sm text-red-800 font-bold">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4 border-b pb-2"><i class="fa-solid fa-user-tag text-pln-cyan mr-1"></i> Informasi Peminjam</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-500 mb-1">Nama Pegawai</p>
                        <p class="font-bold text-gray-800">{{ $peminjaman->user->nama_lengkap }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 mb-1">Unit Tujuan Operasional</p>
                        <p class="font-bold text-gray-800">{{ $peminjaman->unit_tujuan->nama_unit }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 mb-1">Keterangan Pekerjaan</p>
                        <p class="font-medium text-gray-700 bg-gray-50 p-3 rounded-xl border border-gray-100">{{ $peminjaman->keterangan_pekerjaan }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4 border-b pb-2"><i class="fa-regular fa-calendar-check text-pln-cyan mr-1"></i> Jadwal & Status</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-500 mb-1">Tanggal Pengajuan</p>
                        <p class="font-bold text-gray-800">{{ \Carbon\Carbon::parse($peminjaman->tanggal_pengajuan)->format('d M Y, H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 mb-1">Estimasi Pengembalian</p>
                        <p class="font-bold text-red-600">{{ \Carbon\Carbon::parse($peminjaman->estimasi_kembali)->format('d M Y, H:i') }}</p>
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
                        <span class="inline-block px-4 py-1.5 rounded-xl text-[10px] uppercase font-black tracking-wider border {{ $colorClass }}">
                            {{ $peminjaman->status_peminjaman }}
                        </span>
                    </div>
                </div>
            </div>

            @if(!empty($peminjaman->foto_pengembalian))
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4 border-b pb-2"><i class="fa-solid fa-camera text-pln-cyan mr-1"></i> Bukti Fisik Pengembalian</h3>
                <a href="{{ asset('storage/' . $peminjaman->foto_pengembalian) }}" target="_blank" class="block group relative rounded-xl overflow-hidden border-2 border-gray-100 shadow-sm cursor-pointer">
                    <img src="{{ asset('storage/' . $peminjaman->foto_pengembalian) }}" alt="Foto Bukti" class="w-full h-auto object-cover group-hover:scale-105 transition-transform duration-300">
                    <div class="absolute inset-0 bg-pln-dark/50 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 backdrop-blur-sm">
                        <i class="fa-solid fa-magnifying-glass-plus text-white text-3xl mb-2"></i>
                        <span class="text-white font-bold text-xs uppercase tracking-wider">Perbesar Foto</span>
                    </div>
                </a>
            </div>
            @else
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4 border-b pb-2"><i class="fa-solid fa-camera text-pln-cyan mr-1"></i> Bukti Fisik Pengembalian</h3>
                <div class="bg-gray-50 border-2 border-dashed border-gray-200 rounded-xl p-6 text-center text-gray-400">
                    <i class="fa-solid fa-image text-3xl mb-2"></i>
                    <p class="text-sm font-medium">Belum ada foto bukti pengembalian</p>
                </div>
            </div>
            @endif

        </div>

        <div class="lg:col-span-2 space-y-6">
            
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="font-bold text-gray-800"><i class="fa-solid fa-boxes-stacked text-pln-cyan mr-1"></i> Daftar Alat yang Dipinjam</h3>
                </div>
                
                @if($peminjaman->status_peminjaman == 'Sedang Dipinjam')
                <form action="{{ route('admin.peminjaman.pengembalian', $peminjaman->id) }}" method="POST" enctype="multipart/form-data" onsubmit="return confirm('Apakah Anda yakin ingin memproses pengembalian ini?')">
                    @csrf
                @endif

                <div class="overflow-x-auto p-2">
                    <table class="w-full text-left">
                        <thead class="text-xs uppercase text-gray-400 font-bold border-b border-gray-100 bg-white">
                            <tr>
                                <th class="px-4 py-3">Barcode Fisik</th>
                                <th class="px-4 py-3">Nama Alat</th>
                                <th class="px-4 py-3 text-center">Kondisi Keluar</th>
                                @if(in_array($peminjaman->status_peminjaman, ['Sedang Dipinjam', 'Dikembalikan']))
                                    <th class="px-4 py-3 text-right">Kondisi Kembali</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($peminjaman->detail_peminjaman as $detail)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-4 font-mono text-sm text-pln-cyan font-bold">{{ $detail->item_inventaris->kode_barcode }}</td>
                                <td class="px-4 py-4 text-sm font-bold text-gray-800">{{ $detail->item_inventaris->peralatan->nama_alat }}</td>
                                <td class="px-4 py-4 text-center">
                                    <span class="px-3 py-1 bg-green-50 text-green-700 text-[10px] font-black uppercase rounded-lg border border-green-100">
                                        {{ $detail->kondisi_saat_dipinjam }}
                                    </span>
                                </td>
                                
                                @if($peminjaman->status_peminjaman == 'Sedang Dipinjam')
                                    <td class="px-4 py-4 text-right">
                                        <div class="flex flex-col items-end gap-2">
                                            <select name="kondisi_kembali[{{ $detail->id }}]" class="text-sm font-bold rounded-xl border-2 border-gray-200 focus:ring-0 focus:border-pln-cyan w-36" required>
                                                <option value="" disabled selected>Pilih Kondisi...</option>
                                                <option value="Baik">Baik</option>
                                                <option value="Rusak Ringan">Rusak Ringan</option>
                                                <option value="Rusak Berat">Rusak Berat</option>
                                                <option value="Hilang">Hilang</option>
                                            </select>
                                            <input type="text" name="catatan_kerusakan[{{ $detail->id }}]" placeholder="Catatan (Opsional)..." class="text-xs font-medium rounded-xl border-2 border-gray-100 w-full sm:w-48 focus:ring-0 focus:border-pln-cyan bg-gray-50 focus:bg-white transition-colors">
                                        </div>
                                    </td>
                                @elseif($peminjaman->status_peminjaman == 'Dikembalikan')
                                    <td class="px-4 py-4 text-right text-sm">
                                        <span class="px-3 py-1 text-[10px] uppercase font-black tracking-wider rounded-lg border {{ $detail->kondisi_saat_kembali == 'Baik' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-red-50 text-red-700 border-red-200' }}">
                                            {{ $detail->kondisi_saat_kembali }}
                                        </span>
                                        @if($detail->catatan_kerusakan)
                                            <p class="text-[11px] text-gray-500 mt-2 font-medium bg-gray-50 p-2 rounded-lg border border-gray-100 shadow-inner">"{{ $detail->catatan_kerusakan }}"</p>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($peminjaman->status_peminjaman == 'Sedang Dipinjam')
                    <div class="p-6 border-t border-gray-100 bg-white">
                        <label class="block text-sm font-bold text-gray-800 mb-3">
                            <i class="fa-solid fa-camera mr-1 text-pln-cyan"></i> Upload Foto Bukti (Bila Perlu) 
                            <span class="text-gray-400 font-normal text-xs ml-1">(Bisa dikosongkan jika Pegawai sudah unggah foto)</span>
                        </label>
                        <input type="file" name="foto_pengembalian" accept="image/jpeg, image/png, image/jpg"
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-6 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-blue-50 file:text-pln-cyan hover:file:bg-blue-100 transition-colors border-2 border-gray-100 rounded-xl cursor-pointer bg-gray-50 shadow-sm">
                    </div>

                    <div class="p-6 bg-yellow-50 border-t-2 border-yellow-100 flex flex-col sm:flex-row justify-between items-center gap-4">
                        <p class="text-sm font-bold text-yellow-800"><i class="fa-solid fa-triangle-exclamation mr-1"></i> Pastikan fisik alat dan kelengkapannya sudah sesuai sebelum memproses!</p>
                        <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-pln-cyan hover:bg-[#008Cca] text-white font-bold rounded-xl shadow-lg shadow-pln-cyan/30 transition-all flex items-center justify-center gap-2">
                            <i class="fa-solid fa-box-check"></i> Proses Pengembalian Alat
                        </button>
                    </div>
                </form>
                @endif
            </div>

            @if($peminjaman->status_peminjaman == 'Menunggu Verifikasi')
            <div class="bg-white p-6 rounded-3xl shadow-sm border-2 border-pln-cyan/30 flex flex-col sm:flex-row gap-6 items-center justify-between relative overflow-hidden">
                <div class="absolute top-0 right-0 w-40 h-40 bg-pln-cyan/5 rounded-full -mr-10 -mt-10 pointer-events-none"></div>

                <div class="relative z-10 w-full sm:w-auto text-center sm:text-left">
                    <h3 class="font-bold text-gray-800 text-lg">Verifikasi Permohonan</h3>
                    <p class="text-sm text-gray-500 mt-1 font-medium">Tinjau stok alat dan tujuan operasional sebelum persetujuan.</p>
                </div>
                
                <div class="flex w-full sm:w-auto gap-3 relative z-10">
                    <form action="{{ route('admin.peminjaman.verifikasi', $peminjaman->id) }}" method="POST" class="flex-1 sm:flex-none">
                        @csrf
                        <input type="hidden" name="status_peminjaman" value="Ditolak">
                        <button type="submit" class="w-full px-6 py-3 bg-white border-2 border-red-500 text-red-500 hover:bg-red-50 font-bold rounded-xl transition-all" onclick="return confirm('Apakah Anda yakin ingin menolak permohonan ini?')">
                            <i class="fa-solid fa-xmark mr-1"></i> Tolak
                        </button>
                    </form>

                    <form action="{{ route('admin.peminjaman.verifikasi', $peminjaman->id) }}" method="POST" class="flex-1 sm:flex-none">
                        @csrf
                        <input type="hidden" name="status_peminjaman" value="Disetujui">
                        <button type="submit" class="w-full px-6 py-3 bg-pln-cyan hover:bg-[#008Cca] text-white font-bold rounded-xl shadow-lg shadow-pln-cyan/30 transition-all" onclick="return confirm('Apakah Anda yakin ingin menyetujui dan mengeluarkan alat ini?')">
                            <i class="fa-solid fa-check mr-1"></i> Setujui & Keluarkan
                        </button>
                    </form>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection