<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PeminjamanExport;

class RekapController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $status = $request->input('status');

        $query = Peminjaman::with(['user', 'unit_tujuan', 'detail_peminjaman.item_inventaris.peralatan']);

        if ($startDate && $endDate) {
            $query->whereBetween('tanggal_pengajuan', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }
        if ($status) {
            $query->where('status_peminjaman', $status);
        }

        $rekap = $query->orderBy('tanggal_pengajuan', 'desc')->paginate(15)->withQueryString();

        $stats = [
            'total_aset' => \App\Models\ItemInventaris::count(),
            'tersedia' => \App\Models\ItemInventaris::where('status_ketersediaan', 'Tersedia')->count(),
            'dipinjam' => \App\Models\ItemInventaris::where('status_ketersediaan', 'Dipinjam')->count(),
        ];

        return view('supervisor.rekap.index', compact('rekap', 'startDate', 'endDate', 'status', 'stats'));
    }

    public function exportPdf(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $status = $request->status;

        $query = Peminjaman::with(['user', 'unit_tujuan', 'detail_peminjaman.item_inventaris.peralatan']);

        if ($startDate && $endDate) {
            $query->whereBetween('tanggal_pengajuan', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }
        if ($status) {
            $query->where('status_peminjaman', $status);
        }

        $data = $query->orderBy('tanggal_pengajuan', 'desc')->get();
        
        $stats = [
            'total_aset' => \App\Models\ItemInventaris::count(),
            'tersedia' => \App\Models\ItemInventaris::where('status_ketersediaan', 'Tersedia')->count(),
            'dipinjam' => \App\Models\ItemInventaris::where('status_ketersediaan', 'Dipinjam')->count(),
        ];
        
        $pdf = Pdf::loadView('supervisor.rekap.pdf', compact('data', 'startDate', 'endDate', 'status', 'stats'))
                  ->setPaper('a4', 'landscape');
                  
        return $pdf->download('Laporan_Peminjaman_PLN_' . date('Ymd') . '.pdf');
    }

    public function exportExcel(Request $request) 
    {
        $stats = [
            'total_aset' => \App\Models\ItemInventaris::count(),
            'tersedia' => \App\Models\ItemInventaris::where('status_ketersediaan', 'Tersedia')->count(),
            'dipinjam' => \App\Models\ItemInventaris::where('status_ketersediaan', 'Dipinjam')->count(),
        ];

        return Excel::download(
            new PeminjamanExport($request->start_date, $request->end_date, $request->status, $stats), 
            'Rekap_Peminjaman_PLN_' . date('Ymd') . '.xlsx'
        );
    }
}