<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        // 1. Mengambil metrik ringkas untuk kartu dashboard
        $menunggu_verifikasi = Peminjaman::where('user_id', $userId)
                                         ->where('status_peminjaman', 'Menunggu Verifikasi')
                                         ->count();

        $sedang_dipinjam = Peminjaman::where('user_id', $userId)
                                     ->where('status_peminjaman', 'Sedang Dipinjam')
                                     ->count();

        // 2. Mengambil data "Status Permohonan Terkini" (Menunggu atau Ditolak)
        $permohonan_terkini = Peminjaman::with('unit_tujuan')
                                        ->where('user_id', $userId)
                                        ->whereIn('status_peminjaman', ['Menunggu Verifikasi', 'Ditolak'])
                                        ->orderBy('tanggal_pengajuan', 'desc')
                                        ->take(5) // Batasi 5 terbaru agar dashboard rapi
                                        ->get();

        // 3. Mengambil data "Alat yang Sedang Dipegang/Dipinjam" beserta detail itemnya
        $alat_dipegang = Peminjaman::with(['unit_tujuan', 'detail_peminjaman.item_inventaris.peralatan'])
                                   ->where('user_id', $userId)
                                   ->where('status_peminjaman', 'Sedang Dipinjam')
                                   ->orderBy('estimasi_kembali', 'asc') // Urutkan dari yang paling mendekati deadline pengembalian
                                   ->get();

        return view('pegawai.dashboard', compact(
            'menunggu_verifikasi', 
            'sedang_dipinjam', 
            'permohonan_terkini', 
            'alat_dipegang'
        ));
    }
}