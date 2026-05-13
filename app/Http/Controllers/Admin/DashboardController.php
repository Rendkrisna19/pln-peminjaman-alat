<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ItemInventaris;
use App\Models\Peminjaman;
use App\Models\Peralatan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Mengambil metrik real-time untuk dashboard
        $data = [
            'total_alat'      => ItemInventaris::count(),
            'alat_tersedia'   => ItemInventaris::where('status_ketersediaan', 'Tersedia')->count(),
            'alat_dipinjam'   => ItemInventaris::where('status_ketersediaan', 'Dipinjam')->count(),
            'alat_rusak'      => ItemInventaris::where('kondisi', 'Rusak')->count(),
            
            // Mengambil 5 peminjaman terbaru untuk tabel aktivitas
            'recent_activities' => Peminjaman::with(['user', 'unit_tujuan'])
                                    ->latest()
                                    ->take(5)
                                    ->get(),
        ];

        return view('admin.dashboard', $data);
    }
}