<?php

namespace App\Exports;

use App\Models\Peminjaman;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PeminjamanExport implements FromView, ShouldAutoSize, WithStyles
{
    protected $startDate, $endDate, $status, $stats;

    public function __construct($startDate, $endDate, $status, $stats)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->status = $status;
        $this->stats = $stats;
    }

    public function view(): View
    {
        $query = Peminjaman::with(['user', 'unit_tujuan', 'detail_peminjaman.item_inventaris.peralatan']);

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tanggal_pengajuan', [$this->startDate . ' 00:00:00', $this->endDate . ' 23:59:59']);
        }
        if ($this->status) {
            $query->where('status_peminjaman', $this->status);
        }

        $data = $query->orderBy('tanggal_pengajuan', 'desc')->get();

        return view('supervisor.rekap.excel', [
            'data' => $data,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'status' => $this->status,
            'stats' => $this->stats
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // The styling is mostly handled by the inline styles in the blade view.
        ];
    }
}