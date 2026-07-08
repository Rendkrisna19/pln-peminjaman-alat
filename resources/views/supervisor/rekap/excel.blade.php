<table>
    <thead>
        <!-- Title Row -->
        <tr>
            <th colspan="8" style="text-align: center; font-size: 16px; font-weight: bold;">
                REKAPITULASI PEMINJAMAN PERALATAN KERJA - PT PLN (PERSERO)
            </th>
        </tr>
        
        <!-- Period Row -->
        <tr>
            <th colspan="8" style="text-align: center; font-size: 12px; font-style: italic;">
                Periode: 
                @if($startDate && $endDate)
                    {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} s.d {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
                @else
                    Keseluruhan Data
                @endif
                @if($status) | Status: {{ $status }} @endif
            </th>
        </tr>

        <!-- Empty Row -->
        <tr><th colspan="8"></th></tr>
        


        <!-- Empty Row -->
        <tr><th colspan="8"></th></tr>

        <!-- Table Headers -->
        <tr>
            <th style="background-color: #00A2E9; color: #FFFFFF; font-weight: bold; text-align: center;">NO. TRANSAKSI</th>
            <th style="background-color: #00A2E9; color: #FFFFFF; font-weight: bold; text-align: center;">TANGGAL PENGAJUAN</th>
            <th style="background-color: #00A2E9; color: #FFFFFF; font-weight: bold; text-align: center;">NAMA PEMINJAM</th>
            <th style="background-color: #00A2E9; color: #FFFFFF; font-weight: bold; text-align: center;">LOKASI PEKERJAAN (UNIT)</th>
            <th style="background-color: #00A2E9; color: #FFFFFF; font-weight: bold; text-align: center;">ALAT YANG DIPINJAM (KODE BARANG)</th>
            <th style="background-color: #00A2E9; color: #FFFFFF; font-weight: bold; text-align: center;">JML DIPINJAM</th>
            <th style="background-color: #00A2E9; color: #FFFFFF; font-weight: bold; text-align: center;">SISA STOK ALAT</th>
            <th style="background-color: #00A2E9; color: #FFFFFF; font-weight: bold; text-align: center;">ESTIMASI KEMBALI</th>
            <th style="background-color: #00A2E9; color: #FFFFFF; font-weight: bold; text-align: center;">STATUS SAAT INI</th>
            <th style="background-color: #00A2E9; color: #FFFFFF; font-weight: bold; text-align: center;">KETERANGAN / URGENSI</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $peminjaman)
            @php
                $detailAlat = $peminjaman->detail_peminjaman->map(function($detail) {
                    return $detail->item_inventaris->peralatan->nama_alat . ' [' . $detail->item_inventaris->kode_barcode . ']';
                })->implode(', ');

                $jmlDipinjam = $peminjaman->detail_peminjaman->count();

                $sisaStok = $peminjaman->detail_peminjaman->map(function($detail) {
                    $stok = \App\Models\ItemInventaris::where('peralatan_id', $detail->item_inventaris->peralatan_id)->where('status_ketersediaan', 'Tersedia')->count();
                    return $stok . ' Unit';
                })->implode(', ');
            @endphp
            <tr>
                <td>{{ $peminjaman->kode_peminjaman }}</td>
                <td>{{ \Carbon\Carbon::parse($peminjaman->tanggal_pengajuan)->format('d/m/Y H:i') }}</td>
                <td>{{ $peminjaman->user->nama_lengkap ?? 'User Tidak Diketahui' }}</td>
                <td>{{ $peminjaman->unit_tujuan->nama_unit ?? '-' }}</td>
                <td>{{ $detailAlat }}</td>
                <td style="text-align: center;">{{ $jmlDipinjam }}</td>
                <td>{{ $sisaStok }}</td>
                <td>{{ \Carbon\Carbon::parse($peminjaman->estimasi_kembali)->format('d/m/Y H:i') }}</td>
                <td>{{ $peminjaman->status_peminjaman }}</td>
                <td>{{ $peminjaman->keterangan_pekerjaan }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
