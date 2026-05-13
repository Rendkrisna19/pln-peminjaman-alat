<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Peminjaman Alat PLN</title>
    <style>
        @page { margin: 30px 40px; }
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 10px; color: #333; line-height: 1.4; }
        
        /* Kop Surat (Header) */
        .kop-surat { width: 100%; border-bottom: 3px solid #00A2E9; padding-bottom: 10px; margin-bottom: 20px; }
        .kop-surat td { vertical-align: middle; border: none; padding: 0; }
        .perusahaan { font-size: 16px; font-weight: bold; color: #00A2E9; text-transform: uppercase; margin-bottom: 2px; }
        .unit { font-size: 12px; font-weight: bold; color: #333; }
        .alamat { font-size: 9px; color: #666; }
        
        /* Judul Laporan */
        .judul-laporan { text-align: center; font-size: 14px; font-weight: bold; margin-bottom: 5px; text-transform: uppercase; }
        .periode { text-align: center; font-size: 10px; margin-bottom: 20px; color: #555; }
        
        /* Tabel Data */
        table.data-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.data-table th, table.data-table td { border: 1px solid #bdc3c7; padding: 6px 8px; vertical-align: top; }
        
        /* HEADER TABEL WARNA BIRU PLN */
        table.data-table th { 
            background-color: #00A2E9; 
            color: #ffffff; 
            font-weight: bold; 
            text-transform: uppercase; 
            font-size: 9px; 
            text-align: center;
        }
        
        table.data-table td { font-size: 9px; }
        .text-center { text-align: center; }
        .status-badge { font-weight: bold; text-transform: uppercase; }
        
        /* Tanda Tangan */
        .signature-container { width: 100%; margin-top: 30px; }
        .signature-box { width: 250px; float: right; text-align: center; }
        .signature-box p { margin: 2px 0; }
        .signature-space { height: 60px; }
    </style>
</head>
<body>

    <table class="kop-surat">
        <tr>
            <td>
                <div class="perusahaan">PT PLN (Persero)</div>
                <div class="unit">Unit Pelaksana (UP) Pandan</div>
                <div class="alamat">Sistem Informasi Manajemen Peminjaman & Monitoring Peralatan Kerja (E-Tools)</div>
            </td>
        </tr>
    </table>

    <div class="judul-laporan">Rekapitulasi Peminjaman Peralatan Kerja</div>
    <div class="periode">
        Periode: 
        @if($startDate && $endDate)
            {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} s.d {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
        @else
            Keseluruhan Data
        @endif
        @if($status) | Status: {{ $status }} @endif
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="12%">No. Transaksi</th>
                <th width="10%">Tanggal</th>
                <th width="15%">Peminjam</th>
                <th width="15%">Lokasi Pekerjaan</th>
                <th width="35%">Rincian Alat & Barcode</th>
                <th width="10%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center"><b>{{ $item->kode_peminjaman }}</b></td>
                <td class="text-center">{{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->format('d/m/Y') }}</td>
                <td>{{ $item->user->nama_lengkap }}</td>
                <td>{{ $item->unit_tujuan->nama_unit ?? '-' }}</td>
                <td>
                    <ul style="margin: 0; padding-left: 15px;">
                    @foreach($item->detail_peminjaman as $det)
                        <li>{{ $det->item_inventaris->peralatan->nama_alat }} <br> <i>[{{ $det->item_inventaris->kode_barcode }}]</i></li>
                    @endforeach
                    </ul>
                </td>
                <td class="text-center status-badge">{{ $item->status_peminjaman }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Tidak ada data transaksi pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="signature-container">
        <div class="signature-box">
            <p>Pandan, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            <p><strong>Supervisor Logistik</strong></p>
            <div class="signature-space"></div>
            <p><strong>______________________________</strong></p>
            <p>NIP. </p>
        </div>
    </div>

</body>
</html>