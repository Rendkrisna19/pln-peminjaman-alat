<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ItemInventaris;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AsetExport;

class LaporanAsetController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $kondisi = $request->input('kondisi');

        // Statistik Cepat
        $stats = [
            'total' => ItemInventaris::count(),
            'baik'  => ItemInventaris::where('kondisi', 'Baik')->count(),
            'rusak_ringan' => ItemInventaris::where('kondisi', 'Rusak Ringan')->count(),
            'rusak_berat'  => ItemInventaris::where('kondisi', 'Rusak Berat')->count(),
            'tersedia' => ItemInventaris::where('status_ketersediaan', 'Tersedia')->count(),
        ];

        $query = ItemInventaris::with('peralatan.rak');

        if ($search) {
            $query->where('kode_barcode', 'like', "%{$search}%")
                ->orWhereHas('peralatan', function ($q) use ($search) {
                    $q->where('nama_alat', 'like', "%{$search}%");
                });
        }

        if ($kondisi) {
            $query->where('kondisi', $kondisi);
        }

        $aset = $query->orderBy('kode_barcode', 'asc')->paginate(15)->withQueryString();

        return view('admin.laporan_aset.index', compact('aset', 'stats', 'search', 'kondisi'));
    }

    public function exportPdf(Request $request)
    {
        $kondisi = $request->kondisi;
        $query = ItemInventaris::with('peralatan.rak');

        if ($kondisi) {
            $query->where('kondisi', $kondisi);
        }

        $data = $query->orderBy('kode_barcode', 'asc')->get();

        $pdf = Pdf::loadView('admin.laporan_aset.pdf', compact('data', 'kondisi'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('Laporan_Kondisi_Aset_Admin_' . date('Ymd') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new AsetExport($request->kondisi), 'Data_Aset_Admin_' . date('Ymd') . '.xlsx');
    }
}
