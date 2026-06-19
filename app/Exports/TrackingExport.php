<?php

namespace App\Exports;

use App\Models\TrackingLog;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Carbon\Carbon;

class TrackingExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $search, $filterDate, $filterMonth, $sortDirection;

    public function __construct($search, $filterDate, $filterMonth, $sortDirection)
    {
        $this->search = $search;
        $this->filterDate = $filterDate;
        $this->filterMonth = $filterMonth;
        $this->sortDirection = $sortDirection;
    }

    public function query()
    {
        $query = TrackingLog::with(['item_inventaris.peralatan', 'user', 'unit_lokasi']);

        if ($this->search) {
            $search = $this->search;
            $query->whereHas('item_inventaris', function ($q) use ($search) {
                $q->where('kode_barcode', 'like', "%{$search}%")
                  ->orWhereHas('peralatan', function ($subq) use ($search) {
                      $subq->where('nama_alat', 'like', "%{$search}%");
                  });
            });
        }

        if ($this->filterDate) {
            $query->whereDate('tanggal_waktu', $this->filterDate);
        } elseif ($this->filterMonth) {
            $year = date('Y', strtotime($this->filterMonth));
            $month = date('m', strtotime($this->filterMonth));
            $query->whereYear('tanggal_waktu', $year)
                  ->whereMonth('tanggal_waktu', $month);
        }

        return $query->orderBy('tanggal_waktu', $this->sortDirection ?? 'desc');
    }

    public function headings(): array
    {
        return [
            'NO',
            'TANGGAL & WAKTU',
            'BARCODE',
            'NAMA ALAT',
            'AKTIVITAS',
            'LOKASI',
            'PELAKU',
        ];
    }

    public function map($log): array
    {
        static $rowNumber = 0;
        $rowNumber++;

        return [
            $rowNumber,
            Carbon::parse($log->tanggal_waktu)->format('d/m/Y H:i:s') . ' WIB',
            $log->item_inventaris->kode_barcode ?? 'Dihapus',
            $log->item_inventaris->peralatan->nama_alat ?? '-',
            $log->aktivitas,
            $log->unit_lokasi->nama_unit ?? 'Gudang Utama',
            $log->user->nama_lengkap ?? $log->user->name ?? 'Sistem',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['argb' => Color::COLOR_WHITE]
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF00A2E9']
                ],
            ],
        ];
    }
}
