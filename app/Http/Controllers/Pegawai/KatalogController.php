<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Peralatan;
use App\Models\ItemInventaris;
use App\Models\UnitLokasi;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
// Pastikan nama model Rak di bawah ini sesuai dengan yang ada di aplikasi Anda (misal: Rak atau RakPenyimpanan)
use App\Models\RakPenyimpanan as Rak; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class KatalogController extends Controller
{
    // Menampilkan Katalog Alat (Dengan Filter Lengkap)
    public function index(Request $request)
    {
        $search = $request->input('search');
        $filterRak = $request->input('rak_id');
        $filterStatus = $request->input('status'); // 'tersedia' atau 'habis'
        
        $sortField = $request->input('sort_field', 'nama_alat');
        $sortDirection = $request->input('sort_direction', 'asc');

        // Mengambil data Rak untuk Dropdown Filter
        // Jika model Anda bernama RakPenyimpanan, ubah Rak:: menjadi RakPenyimpanan::
        $daftarRak = Rak::orderBy('nama_rak', 'asc')->get();

        // Query Utama
        $query = Peralatan::with('rak')->withCount(['item_inventaris as stok_tersedia' => function ($q) {
            $q->where('status_ketersediaan', 'Tersedia');
        }]);

        // 1. Filter Pencarian (Nama & Spesifikasi)
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_alat', 'like', "%{$search}%")
                  ->orWhere('spesifikasi', 'like', "%{$search}%");
            });
        }

        // 2. Filter Berdasarkan Rak
        if ($filterRak) {
            $query->where('rak_id', $filterRak);
        }

        // 3. Filter Berdasarkan Status Ketersediaan
        if ($filterStatus) {
            if ($filterStatus == 'tersedia') {
                $query->having('stok_tersedia', '>', 0);
            } elseif ($filterStatus == 'habis') {
                $query->having('stok_tersedia', '=', 0);
            }
        }

        // Eksekusi Query dengan Pagination
        $peralatan = $query->orderBy($sortField, $sortDirection)->paginate(12)->withQueryString();

        return view('pegawai.katalog.index', compact('peralatan', 'search', 'filterRak', 'filterStatus', 'daftarRak', 'sortField', 'sortDirection'));
    }

    // =========================================================================
    // METHOD LAINNYA TETAP SAMA (TIDAK ADA PERUBAHAN LOGIKA BISNIS)
    // =========================================================================

    public function formPeminjaman(Request $request, $id)
    {
        $alat = Peralatan::findOrFail($id);
        
        $qty = $request->input('qty', 1);
        
        $stok_tersedia = ItemInventaris::where('peralatan_id', $id)
                            ->where('status_ketersediaan', 'Tersedia')
                            ->count();
                            
        if ($qty > $stok_tersedia) {
            return redirect()->route('pegawai.katalog.index')->with('error', 'Stok alat tidak mencukupi.');
        }

        $unit_lokasi = UnitLokasi::orderBy('nama_unit', 'asc')->get();
        
        return view('pegawai.katalog.form', compact('alat', 'qty', 'unit_lokasi'));
    }

    public function prosesPeminjaman(Request $request, $id)
    {
        $request->validate([
            'qty' => 'required|integer|min:1',
            'unit_tujuan_id' => 'required|exists:tbl_unit_lokasi,id',
            'estimasi_kembali' => 'required|date|after_or_equal:today',
            'keterangan_pekerjaan' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $peralatan = Peralatan::findOrFail($id);
            $qty = $request->qty;

            $items = ItemInventaris::where('peralatan_id', $id)
                ->where('status_ketersediaan', 'Tersedia')
                ->take($qty)
                ->get();

            if ($items->count() < $qty) {
                throw new \Exception("Stok {$peralatan->nama_alat} tidak mencukupi.");
            }

            $peminjaman = Peminjaman::create([
                'kode_peminjaman' => 'TRX-' . date('Ym') . '-' . rand(1000, 9999),
                'user_id' => Auth::id(),
                'unit_tujuan_id' => $request->unit_tujuan_id,
                'tanggal_pengajuan' => now(),
                'estimasi_kembali' => $request->estimasi_kembali,
                'keterangan_pekerjaan' => $request->keterangan_pekerjaan,
                'status_peminjaman' => 'Menunggu Verifikasi',
            ]);

            foreach ($items as $item) {
                DetailPeminjaman::create([
                    'peminjaman_id' => $peminjaman->id,
                    'item_inventaris_id' => $item->id,
                    'kondisi_saat_dipinjam' => $item->kondisi,
                ]);
            }

            DB::commit();

            try {
                $adminEmail = config('mail.from.address');
                
                if (empty($adminEmail)) {
                    throw new \Exception("Email Admin (MAIL_FROM_ADDRESS) masih kosong di file .env");
                }

                Mail::to($adminEmail)->send(new \App\Mail\NotifikasiPeminjaman($peminjaman, 'baru'));
                
            } catch (\Exception $e) {
                // Biarkan log error berjalan di sistem (opsional, disesuaikan dengan env produksi)
            }

            return redirect()->route('pegawai.riwayat.index')->with('success', 'Permohonan berhasil dikirim!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal: ' . $e->getMessage())->withInput();
        }
    }
}