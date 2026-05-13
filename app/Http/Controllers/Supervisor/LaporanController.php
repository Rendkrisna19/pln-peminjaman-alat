<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\ItemInventaris;
use App\Models\TrackingLog;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
// use Maatwebsite\Excel\Facades\Excel; // (Di-uncomment jika sudah membuat file Export Excel)

class LaporanController extends Controller
{
    // A. Laporan Rekapitulasi Peminjaman (Bulanan/Tahunan)
    public function peminjaman(Request $request)
    {
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));
        $format = $request->input('export'); // Cek jika tombol export ditekan (pdf/excel)

        $query = Peminjaman::with(['user', 'unit_tujuan'])
            ->whereMonth('tanggal_pengajuan', $bulan)
            ->whereYear('tanggal_pengajuan', $tahun);

        if ($format === 'pdf') {
            $data = $query->get();
            $pdf = Pdf::loadView('supervisor.laporan.pdf_peminjaman', compact('data', 'bulan', 'tahun'));
            return $pdf->download("Laporan_Peminjaman_{$bulan}_{$tahun}.pdf");
        }

        $laporan = $query->orderBy('tanggal_pengajuan', 'desc')->paginate(15)->withQueryString();
        return view('supervisor.laporan.peminjaman', compact('laporan', 'bulan', 'tahun'));
    }

    // B. Laporan Kondisi Fisik Aset
    public function aset(Request $request)
    {
        $kondisi = $request->input('kondisi'); // Filter Baik/Rusak
        $format = $request->input('export');

        $query = ItemInventaris::with('peralatan.rak');

        if ($kondisi) {
            $query->where('kondisi', $kondisi);
        }

        if ($format === 'pdf') {
            $data = $query->get();
            $pdf = Pdf::loadView('supervisor.laporan.pdf_aset', compact('data', 'kondisi'));
            return $pdf->download("Laporan_Kondisi_Aset.pdf");
        }

        $laporan = $query->orderBy('id', 'desc')->paginate(15)->withQueryString();
        return view('supervisor.laporan.aset', compact('laporan', 'kondisi'));
    }

    // C. Laporan Jejak Lokasi (Tracking Log)
    public function tracking(Request $request)
    {
        $search = $request->input('search'); // Cari berdasarkan unit/lokasi
        $format = $request->input('export');

        $query = TrackingLog::with(['item_inventaris.peralatan', 'user', 'unit_lokasi']);

        if ($search) {
            $query->whereHas('unit_lokasi', function($q) use ($search) {
                $q->where('nama_unit', 'like', "%{$search}%");
            });
        }

        if ($format === 'pdf') {
            $data = $query->get();
            $pdf = Pdf::loadView('supervisor.laporan.pdf_tracking', compact('data', 'search'))
                      ->setPaper('a4', 'landscape'); // Format landscape karena kolomnya banyak
            return $pdf->download("Laporan_Tracking_Alat.pdf");
        }

        $laporan = $query->orderBy('tanggal_waktu', 'desc')->paginate(15)->withQueryString();
        return view('supervisor.laporan.tracking', compact('laporan', 'search'));
    }
}