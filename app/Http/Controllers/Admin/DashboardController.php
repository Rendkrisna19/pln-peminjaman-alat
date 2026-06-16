<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ItemInventaris;
use App\Models\Peminjaman;
use App\Models\Peralatan;
use App\Models\RakPenyimpanan;
use App\Models\UnitLokasi;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Mengambil metrik real-time untuk dashboard
        $data = [
            // Total unit fisik aset yang terdaftar
            'total_alat'        => ItemInventaris::count(),

            // Unit yang siap dipinjam
            'alat_tersedia'     => ItemInventaris::where('status_ketersediaan', 'Tersedia')->count(),

            // Unit yang sedang dipinjam (berdasarkan status_ketersediaan)
            'alat_dipinjam'     => ItemInventaris::where('status_ketersediaan', 'Dipinjam')->count(),

            // Unit yang rusak: cocokkan 'Rusak Ringan' dan 'Rusak Berat' (enum value asli di DB)
            'alat_rusak'        => ItemInventaris::where('kondisi', 'like', 'Rusak%')->count(),

            // Total katalog alat (jenis/tipe peralatan)
            'total_peralatan'   => Peralatan::count(),

            // Total peminjaman semua status
            'total_peminjaman'  => Peminjaman::count(),

            // Peminjaman yang menunggu verifikasi admin
            'pending_peminjaman' => Peminjaman::where('status_peminjaman', 'Menunggu Verifikasi')->count(),

            // Total user terdaftar (non-admin)
            'total_user'        => User::where('role', 'pegawai')->count(),

            // Mengambil 5 peminjaman terbaru untuk tabel aktivitas
            'recent_activities' => Peminjaman::with(['user', 'unit_tujuan'])
                                    ->latest()
                                    ->take(5)
                                    ->get(),
        ];

        return view('admin.dashboard', $data);
    }
}