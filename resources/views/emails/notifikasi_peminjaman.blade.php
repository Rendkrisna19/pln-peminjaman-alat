<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi Peminjaman</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f3f4f6; margin: 0; padding: 20px; color: #333; }
        .container { max-width: 600px; background-color: #ffffff; margin: 0 auto; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header { text-align: center; padding-bottom: 20px; border-bottom: 1px solid #e5e7eb; margin-bottom: 20px; }
        .header h2 { margin: 0; color: #1f2937; }
        .status-badge { display: inline-block; padding: 8px 16px; border-radius: 9999px; font-weight: bold; font-size: 14px; text-transform: uppercase; margin-bottom: 15px; }
        .status-disetujui { background-color: #d1fae5; color: #065f46; }
        .status-ditolak { background-color: #fee2e2; color: #991b1b; }
        .status-baru { background-color: #dbeafe; color: #1e40af; }
        .status-dikembalikan { background-color: #f3e8ff; color: #6b21a8; }
        .content { line-height: 1.6; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        table th, table td { border: 1px solid #d1d5db; padding: 10px; text-align: left; font-size: 14px; }
        table th { background-color: #f9fafb; font-weight: bold; }
        .footer { margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; font-size: 12px; text-align: center; color: #6b7280; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Sistem E-Tools PLN</h2>
            <p style="margin: 5px 0 0; color: #6b7280;">Pemberitahuan Status Transaksi Alat</p>
        </div>

        <div class="content">
            @if($jenisNotif === 'baru')
                <p>Halo <strong>Admin </strong>,</p>
                <p>Terdapat permohonan peminjaman alat baru yang menunggu verifikasi Anda dengan status:</p>
                <div class="status-badge status-baru">Menunggu Verifikasi</div>
            @else
                <p>Halo, <strong>{{ $peminjaman->user->nama_lengkap ?? 'Pegawai' }}</strong>,</p>
                
                @if($jenisNotif === 'disetujui')
                    <p>Permohonan peminjaman alat Anda telah direspons oleh Admin dengan status:</p>
                    <div class="status-badge status-disetujui">Disetujui</div>
                    <p>Silakan ambil alat tersebut di ruang inventaris/gudang.</p>
                @elseif($jenisNotif === 'ditolak')
                    <p>Mohon maaf, permohonan peminjaman alat Anda telah direspons oleh Admin dengan status:</p>
                    <div class="status-badge status-ditolak">Ditolak</div>
                    <p>Silakan hubungi Admin untuk informasi lebih lanjut.</p>
                @elseif($jenisNotif === 'dikembalikan')
                    <p>Terima kasih! Alat yang Anda pinjam telah berhasil direkam oleh sistem dengan status:</p>
                    <div class="status-badge status-dikembalikan">Dikembalikan ke Gudang</div>
                @elseif($jenisNotif === 'pengingat')
                    <p>Mengingatkan Anda bahwa batas waktu pengembalian alat peminjaman ini adalah <strong>H-1 (Satu Hari Lagi)</strong>.</p>
                    <div class="status-badge status-baru" style="background-color: #fef08a; color: #854d0e;">Pengingat H-1</div>
                    <p>Mohon pastikan alat segera dikembalikan dalam kondisi lengkap ke ruang inventaris/gudang.</p>
                @endif
            @endif

            <h3>Detail Transaksi:</h3>
            <table>
                <tr>
                    <th>Kode Peminjaman</th>
                    <td style="font-family: monospace; font-weight: bold; color: #00A2E9;">{{ $peminjaman->kode_peminjaman }}</td>
                </tr>
                <tr>
                    <th>Nama Peminjam</th>
                    <td>{{ $peminjaman->user->nama_lengkap ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Tujuan Lokasi</th>
                    <td>{{ $peminjaman->unit_tujuan->nama_unit ?? '-' }}</td>
                </tr>
            </table>

            @if($peminjaman->detail_peminjaman && $peminjaman->detail_peminjaman->count() > 0)
            <h3>Daftar Alat:</h3>
            <table>
                <thead>
                    <tr>
                        <th>Kode Barang</th>
                        <th>Nama Alat</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($peminjaman->detail_peminjaman as $detail)
                    <tr>
                        <td style="font-family: monospace;">{{ $detail->item_inventaris->kode_barcode ?? '-' }}</td>
                        <td>{{ $detail->item_inventaris->peralatan->nama_alat ?? 'Alat' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>

        <div class="footer">
            <p>Email ini dikirim secara otomatis oleh sistem. Mohon untuk tidak membalas email ini.</p>
            <p>&copy; {{ date('Y') }} PT PLN (Persero). All rights reserved.</p>
        </div>
    </div>
</body>
</html>