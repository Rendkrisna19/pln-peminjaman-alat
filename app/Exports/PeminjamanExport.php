<?php

namespace App\Exports;

use App\Models\Peminjaman;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Border;

class PeminjamanExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $startDate, $endDate, $status;

    public function __construct($startDate, $endDate, $status)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->status = $status;
    }

    public function query()
    {
        $query = Peminjaman::with(['user', 'unit_tujuan', 'detail_peminjaman.item_inventaris.peralatan']);

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tanggal_pengajuan', [$this->startDate . ' 00:00:00', $this->endDate . ' 23:59:59']);
        }
        if ($this->status) {
            $query->where('status_peminjaman', $this->status);
        }

        return $query->orderBy('tanggal_pengajuan', 'desc');
    }

    // Header Kolom di Excel
    public function headings(): array
    {
        return [
            'NO. TRANSAKSI',
            'TANGGAL PENGAJUAN',
            'NAMA PEMINJAM',
            'LOKASI PEKERJAAN (UNIT)',
            'ALAT YANG DIPINJAM (BARCODE)',
            'ESTIMASI KEMBALI',
            'STATUS SAAT INI',
            'KETERANGAN / URGENSI'
        ];
    }

    // Mapping isi data baris per baris
    public function map($peminjaman): array
    {
        // Menggabungkan semua nama alat dan barcode ke dalam satu sel teks, dipisah koma
        $detailAlat = $peminjaman->detail_peminjaman->map(function($detail) {
            return $detail->item_inventaris->peralatan->nama_alat . ' [' . $detail->item_inventaris->kode_barcode . ']';
        })->implode(', ');

        return [
            $peminjaman->kode_peminjaman,
            \Carbon\Carbon::parse($peminjaman->tanggal_pengajuan)->format('d/m/Y H:i'),
            $peminjaman->user->nama_lengkap ?? 'User Tidak Diketahui',
            $peminjaman->unit_tujuan->nama_unit ?? '-',
            $detailAlat,
            \Carbon\Carbon::parse($peminjaman->estimasi_kembali)->format('d/m/Y H:i'),
            $peminjaman->status_peminjaman,
            $peminjaman->keterangan_pekerjaan
        ];
    }

    // Styling khusus untuk Excel (Warna Biru PLN untuk Header)
    public function styles(Worksheet $sheet)
    {
        return [
            // Style untuk Baris Pertama (Header)
            1 => [
                'font' => [
                    'bold' => true, 
                    'color' => ['argb' => Color::COLOR_WHITE] // Teks Putih
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF00A2E9'] // Kode Warna Biru PLN (#00A2E9) dengan Opacity Full (FF)
                ],
            ],
        ];
    }
}