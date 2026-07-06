<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Kondisi Aset Fisik PLN - Admin</title>
    <style>
        @page { margin: 30px 40px; }
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 10px; color: #333; line-height: 1.4; }
        
        .kop-surat { width: 100%; border-bottom: 3px solid #00A2E9; padding-bottom: 10px; margin-bottom: 20px; }
        .perusahaan { font-size: 16px; font-weight: bold; color: #00A2E9; text-transform: uppercase; }
        .unit { font-size: 12px; font-weight: bold; color: #333; }
        
        .judul-laporan { text-align: center; font-size: 14px; font-weight: bold; margin-bottom: 5px; text-transform: uppercase; }
        .periode { text-align: center; font-size: 10px; margin-bottom: 20px; color: #555; }
        
        table.data-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.data-table th, table.data-table td { border: 1px solid #bdc3c7; padding: 7px; vertical-align: middle; }
        
        table.data-table th { 
            background-color: #00A2E9; color: #ffffff; font-weight: bold; 
            text-transform: uppercase; font-size: 9px; text-align: center;
        }
        
        .text-center { text-align: center; }
        
        .kondisi-baik { color: #16a085; font-weight: bold; text-transform: uppercase; }
        .kondisi-rusak-ringan { color: #f39c12; font-weight: bold; text-transform: uppercase; }
        .kondisi-rusak-berat { color: #c0392b; font-weight: bold; text-transform: uppercase; }
        
        .signature-container { width: 100%; margin-top: 40px; }
        .signature-box { width: 250px; float: right; text-align: center; }
        .signature-space { height: 60px; }
    </style>
</head>
<body>

    <table class="kop-surat">
        <tr>
            <td style="border: none;">
                <div class="perusahaan">PT PLN (Persero)</div>
                <div class="unit">Unit pembangkit (UP) Pandan</div>
                <div style="font-size: 9px; color: #666;">E-Tools Manajemen Peralatan Kerja</div>
            </td>
        </tr>
    </table>

    <div class="judul-laporan">Laporan Inventaris & Kondisi Fisik Aset</div>
    <div class="periode">
        Status Filter: {{ $kondisi ?: 'Semua Kondisi' }} | Dicetak: {{ date('d/m/Y H:i') }}
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Kode Barang</th>
                <th width="35%">Nama Alat & Spesifikasi</th>
                <th width="15%">Lokasi Rak</th>
                <th width="15%">Kondisi</th>
                <th width="15%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center"><b>{{ $item->kode_barcode }}</b></td>
                <td>
                    <b>{{ $item->peralatan->nama_alat ?? '-' }}</b><br>
                    <span style="font-size: 8px; color:#777;">{{ $item->peralatan->spesifikasi ?? '-' }}</span>
                </td>
                <td class="text-center">{{ $item->peralatan->rak->nama_rak ?? '-' }}</td>
                <td class="text-center {{ 
                    $item->kondisi == 'Baik' ? 'kondisi-baik' : 
                    ($item->kondisi == 'Rusak Ringan' ? 'kondisi-rusak-ringan' : 'kondisi-rusak-berat') 
                }}">
                    {{ $item->kondisi }}
                </td>
                <td class="text-center" style="font-size: 8px; font-weight: bold;">{{ $item->status_ketersediaan }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Tidak ada data aset tercatat.</td>
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
