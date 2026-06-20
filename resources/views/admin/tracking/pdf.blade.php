<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tracking Log Peralatan - PLN</title>
    <style>
        @page { margin: 30px 40px; }
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 10px; color: #333; line-height: 1.4; }
        
        .kop-surat { width: 100%; border-bottom: 3px solid #00A2E9; padding-bottom: 10px; margin-bottom: 20px; }
        .kop-surat td { vertical-align: middle; border: none; padding: 0; }
        .perusahaan { font-size: 16px; font-weight: bold; color: #00A2E9; text-transform: uppercase; margin-bottom: 2px; }
        .unit { font-size: 12px; font-weight: bold; color: #333; }
        .alamat { font-size: 9px; color: #666; }
        
        .judul-laporan { text-align: center; font-size: 14px; font-weight: bold; margin-bottom: 5px; text-transform: uppercase; }
        .periode { text-align: center; font-size: 10px; margin-bottom: 20px; color: #555; }
        
        table.data-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.data-table th, table.data-table td { border: 1px solid #bdc3c7; padding: 6px 8px; vertical-align: top; }
        
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
                <div class="unit">Unit pembangkit (UP) Pandan</div>
                <div class="alamat">Sistem Informasi Manajemen Peminjaman & Monitoring Peralatan Kerja (E-Tools)</div>
            </td>
        </tr>
    </table>

    <div class="judul-laporan">Tracking Log Peralatan</div>
    <div class="periode">
        @if($filterDate)
            Tanggal: {{ \Carbon\Carbon::parse($filterDate)->format('d M Y') }}
        @elseif($filterMonth)
            Bulan: {{ \Carbon\Carbon::parse($filterMonth . '-01')->translatedFormat('F Y') }}
        @else
            Keseluruhan Data
        @endif
        @if($search) | Pencarian: {{ $search }} @endif
         | Dicetak: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="12%">Tanggal & Waktu</th>
                <th width="13%">Barcode</th>
                <th width="20%">Nama Alat</th>
                <th width="30%">Aktivitas</th>
                <th width="12%">Lokasi</th>
                <th width="10%">Pelaku</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $log)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">
                    {{ \Carbon\Carbon::parse($log->tanggal_waktu)->format('d/m/Y') }}<br>
                    <span style="font-size:8px; color:#888;">{{ \Carbon\Carbon::parse($log->tanggal_waktu)->format('H:i:s') }} WIB</span>
                </td>
                <td class="text-center"><b>{{ $log->item_inventaris->kode_barcode ?? 'Dihapus' }}</b></td>
                <td>{{ $log->item_inventaris->peralatan->nama_alat ?? '-' }}</td>
                <td>{{ $log->aktivitas }}</td>
                <td>{{ $log->unit_lokasi->nama_unit ?? 'Gudang Utama' }}</td>
                <td>{{ $log->user->nama_lengkap ?? $log->user->name ?? 'Sistem' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Tidak ada data tracking log pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="signature-container">
        <div class="signature-box">
            <p>Pandan, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            <p><strong>Admin Gudang</strong></p>
            <div class="signature-space"></div>
            <p><strong>{{ Auth::user()->nama_lengkap }}</strong></p>
            <p>NIP. ............................</p>
        </div>
    </div>

</body>
</html>
