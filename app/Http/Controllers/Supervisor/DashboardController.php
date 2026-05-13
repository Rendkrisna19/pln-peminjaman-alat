<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\ItemInventaris;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Statistik Ringkas (Perbaikan kondisi rusak untuk menangkap Rusak Ringan & Berat)
        $stats = [
            'total_alat'      => ItemInventaris::count(),
            'sedang_dipinjam' => ItemInventaris::where('status_ketersediaan', 'Dipinjam')->count(),
            'kondisi_rusak'   => ItemInventaris::whereIn('kondisi', ['Rusak', 'Rusak Ringan', 'Rusak Berat'])->count(),
            'permintaan_baru' => Peminjaman::where('status_peminjaman', 'Menunggu Verifikasi')->count(),
        ];

        // 2. Data Grafik: Peminjaman 7 Hari Terakhir (Format untuk Bar Chart)
        $chartDataRaw = Peminjaman::select(DB::raw('DATE(tanggal_pengajuan) as date'), DB::raw('count(*) as total'))
            ->where('tanggal_pengajuan', '>=', now()->subDays(6)) // 7 hari (hari ini + 6 hari ke belakang)
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Menyusun array agar tanggal yang kosong tetap memiliki nilai 0
        $chartData = [];
        $labels = [];
        $totals = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $dateStr = now()->subDays($i)->format('Y-m-d');
            $labels[] = now()->subDays($i)->format('d M');
            
            // Cari apakah ada data di tanggal tersebut
            $found = $chartDataRaw->firstWhere('date', $dateStr);
            $totals[] = $found ? $found->total : 0;
        }

        $chartData = [
            'labels' => $labels,
            'data' => $totals
        ];

        // 3. Data Grafik Pie/Doughnut: Kondisi Alat Detail
        $pieData = [
            'Baik' => ItemInventaris::where('kondisi', 'Baik')->count(),
            'Rusak Ringan' => ItemInventaris::where('kondisi', 'Rusak Ringan')->count(),
            'Rusak Berat' => ItemInventaris::whereIn('kondisi', ['Rusak', 'Rusak Berat'])->count(),
        ];

        return view('supervisor.dashboard', compact('stats', 'chartData', 'pieData'));
    }
}