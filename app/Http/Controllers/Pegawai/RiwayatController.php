<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $sortField = $request->input('sort_field', 'tanggal_pengajuan');
        $sortDirection = $request->input('sort_direction', 'desc');
        $filterStatus = $request->input('status_peminjaman');
        $userId = Auth::id();

        // 1. Menghitung Statistik untuk Card Interaktif
        $stats = [
            'total' => Peminjaman::where('user_id', $userId)->count(),
            'menunggu' => Peminjaman::where('user_id', $userId)->where('status_peminjaman', 'Menunggu Verifikasi')->count(),
            'aktif' => Peminjaman::where('user_id', $userId)->where('status_peminjaman', 'Sedang Dipinjam')->count(),
            'selesai' => Peminjaman::where('user_id', $userId)->where('status_peminjaman', 'Dikembalikan')->count(),
            'ditolak' => Peminjaman::where('user_id', $userId)->where('status_peminjaman', 'Ditolak')->count(),
        ];

        // 2. Membangun Query Utama
        $query = Peminjaman::with(['unit_tujuan', 'admin'])->where('user_id', $userId);

        if ($search) {
            // Wajib dibungkus function agar orWhere tidak menembus filter where('user_id')
            $query->where(function($q) use ($search) {
                $q->where('kode_peminjaman', 'like', "%{$search}%")
                  ->orWhere('keterangan_pekerjaan', 'like', "%{$search}%");
            });
        }

        if ($filterStatus) {
            $query->where('status_peminjaman', $filterStatus);
        }

        // 3. Validasi & Terapkan Sorting (Keamanan agar tidak di-inject sembarang field)
        $allowedSorts = ['kode_peminjaman', 'tanggal_pengajuan', 'estimasi_kembali', 'status_peminjaman'];
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDirection);
        }

        // 4. Pagination dengan mempertahankan parameter URL (search, filter, sort)
        $riwayat = $query->paginate(10)->withQueryString();

        return view('pegawai.riwayat.index', compact('riwayat', 'search', 'sortField', 'sortDirection', 'filterStatus', 'stats'));
    }

        public function show($id)
        {
            $peminjaman = Peminjaman::with(['unit_tujuan', 'detail_peminjaman.item_inventaris.peralatan'])
                            ->findOrFail($id);

            // Keamanan: Cegah pegawai melihat riwayat tiket milik pegawai lain
            if ($peminjaman->user_id !== Auth::id()) {
                abort(403, 'Akses Ditolak: Ini bukan tiket Anda.');
            }

            return view('pegawai.riwayat.show', compact('peminjaman'));
        }
}