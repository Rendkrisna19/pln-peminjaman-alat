<!-- resources/views/emails/overdue_reminder.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Pemberitahuan Overdue Alat</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-w-xl mx-auto; p-5; border: 1px solid #ddd; border-radius: 10px;">
        <h2 style="color: #d9534f;">Peringatan Pengembalian Alat!</h2>
        <p>Halo, <strong>{{ $peminjaman->user->nama_lengkap }}</strong>,</p>
        <p>Sistem mencatat bahwa Anda memiliki alat yang belum dikembalikan dan telah melewati batas waktu estimasi yang ditentukan.</p>
        
        <table style="width: 100%; border-collapse: collapse; margin-top: 15px;">
            <tr>
                <td style="padding: 8px; border: 1px solid #ddd; background: #f9f9f9; width: 150px;">Kode Pinjam</td>
                <td style="padding: 8px; border: 1px solid #ddd; font-weight: bold;">{{ $peminjaman->kode_peminjaman }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; border: 1px solid #ddd; background: #f9f9f9;">Batas Waktu</td>
                <td style="padding: 8px; border: 1px solid #ddd; color: red;">{{ \Carbon\Carbon::parse($peminjaman->estimasi_kembali)->format('d F Y, H:i') }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; border: 1px solid #ddd; background: #f9f9f9;">Lokasi Pekerjaan</td>
                <td style="padding: 8px; border: 1px solid #ddd;">{{ $peminjaman->unit_tujuan->nama_unit ?? '-' }}</td>
            </tr>
        </table>

        <p style="margin-top: 20px;">Mohon untuk segera mengembalikan peralatan tersebut ke gudang logistik PLN UP Pandan atau melapor ke admin jika ada perpanjangan masa kerja.</p>
        
        <p>Terima kasih,<br><strong>Admin Logistik</strong></p>
    </div>
</body>
</html>