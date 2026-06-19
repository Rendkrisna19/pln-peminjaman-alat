<?php

namespace App\Exports;

use App\Models\ItemInventaris;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Carbon\Carbon;

class AsetExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $kondisi;

    // Menangkap filter kondisi dari controller
    public function __construct($kondisi)
    {
        $this->kondisi = $kondisi;
    }

    // Mengambil data dari database
    public function query()
    {
        $query = ItemInventaris::with('peralatan.rak');

        // Filter berdasarkan kondisi (Baik, Rusak Ringan, Rusak Berat)
        if ($this->kondisi) {
            $query->where('kondisi', $this->kondisi);
        }

        return $query->orderBy('kode_barcode', 'asc');
    }

    // Membuat Judul Kolom (Header)
    public function headings(): array
    {
        return [
            'NO',
            'KODE BARCODE',
            'NAMA INDUK ALAT',
            'MERK / SPESIFIKASI',
            'LOKASI RAK',
            'KONDISI FISIK',
            'STATUS SAAT INI',
            'TANGGAL MASUK'
        ];
    }

    // Mapping data ke masing-masing kolom
    public function map($item): array
    {
        // Counter untuk nomor urut (opsional, karena FromQuery tidak otomatis memberi nomor)
        static $rowNumber = 0;
        $rowNumber++;

        return [
            $rowNumber,
            $item->kode_barcode,
            $item->peralatan->nama_alat ?? 'Tanpa Nama',
            $item->peralatan->spesifikasi ?? 'Tanpa Spesifikasi',
            $item->peralatan->rak->nama_rak ?? 'Tanpa Rak',
            $item->kondisi, // Ini akan mencetak: Baik, Rusak Ringan, atau Rusak Berat
            $item->status_ketersediaan,
            Carbon::parse($item->created_at)->format('d/m/Y')
        ];
    }

    // Memberikan Styling khusus pada Excel
    public function styles(Worksheet $sheet)
    {
        return [
            // Style khusus untuk Baris 1 (Header Tabel)
            1 => [
                'font' => [
                    'bold' => true, 
                    'color' => ['argb' => Color::COLOR_WHITE] // Teks berwarna putih
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF00A2E9'] // Background Biru Khas PLN (#00A2E9)
                ],
            ],
        ];
    }
}