<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\ItemInventaris;
use App\Models\TrackingLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage; // Pastikan ini di-import

class PengembalianController extends Controller
{
    // Menampilkan Form Pengembalian
    public function form($id)
    {
        $peminjaman = Peminjaman::with(['detail_peminjaman.item_inventaris.peralatan'])->findOrFail($id);

        if ($peminjaman->status_peminjaman !== 'Sedang Dipinjam' || $peminjaman->user_id !== Auth::id()) {
            return redirect()->route('pegawai.riwayat.index')->with('error', 'Akses ditolak. Transaksi tidak valid untuk dikembalikan.');
        }

        return view('pegawai.pengembalian.form', compact('peminjaman'));
    }

    // Memproses Data Pengembalian & Upload Bukti Foto
    public function proses(Request $request, $id)
    {
        $peminjaman = Peminjaman::with('detail_peminjaman.item_inventaris')->findOrFail($id);

        // Validasi input array kondisi dan file foto
        $request->validate([
            'kondisi' => 'required|array',
            'kondisi.*' => 'required|in:Baik,Rusak Ringan,Rusak Berat',
            'catatan_kerusakan' => 'nullable|array',
            'foto_bukti' => 'required|image|mimes:jpeg,png,jpg|max:5120', // Maks 5 MB
        ], [
            'foto_bukti.required' => 'Foto bukti pengembalian wajib diunggah!',
            'foto_bukti.image' => 'File harus berupa gambar (JPG/PNG).',
            'foto_bukti.max' => 'Ukuran foto maksimal adalah 5MB.',
        ]);

        DB::beginTransaction();
        try {
            // Proses Upload Foto Bukti
            $pathFoto = null;
            if ($request->hasFile('foto_bukti')) {
                // Simpan ke storage/app/public/bukti_pengembalian
                $pathFoto = $request->file('foto_bukti')->store('bukti_pengembalian', 'public');
            }

            // 1. Update Header Peminjaman (Masukkan path foto ke database)
            $peminjaman->update([
                'tanggal_dikembalikan' => now(),
                'status_peminjaman' => 'Dikembalikan',
                'foto_pengembalian' => $pathFoto, // PASTIKAN KOLOM INI ADA DI TABEL ANDA
            ]);

            // 2. Looping setiap barang
            foreach ($peminjaman->detail_peminjaman as $detail) {
                $item_id = $detail->item_inventaris_id;
                $kondisi_saat_kembali = $request->kondisi[$item_id];
                $catatan = $request->catatan_kerusakan[$item_id] ?? null;

                // Update Detail Peminjaman
                $detail->update([
                    'kondisi_saat_kembali' => $kondisi_saat_kembali,
                    'catatan_kerusakan' => $catatan,
                ]);

                // Update Master Item Fisik
                $status_stok = ($kondisi_saat_kembali == 'Rusak Berat') ? 'Diperbaiki' : 'Tersedia';
                
                $detail->item_inventaris->update([
                    'kondisi' => $kondisi_saat_kembali,
                    'status_ketersediaan' => $status_stok,
                ]);

                // 3. Simpan Riwayat Pergerakan Alat
                TrackingLog::create([
                    'item_inventaris_id' => $item_id,
                    'peminjaman_id' => $peminjaman->id,
                    'user_id' => Auth::id(),
                    'unit_lokasi_id' => null,
                    'aktivitas' => "Dikembalikan ke gudang. Kondisi: " . $kondisi_saat_kembali,
                    'tanggal_waktu' => now(),
                ]);
            }

            DB::commit();

            try {
                $peminjaman->load(['user']); 
                $emailPegawai = $peminjaman->user->email;
                $emailAdmin = env('MAIL_FROM_ADDRESS', config('mail.from.address'));

                \Illuminate\Support\Facades\Mail::to($emailPegawai)
                    ->cc($emailAdmin)
                    ->send(new \App\Mail\NotifikasiPeminjaman($peminjaman, 'dikembalikan'));
                
                return redirect()->route('pegawai.riwayat.index')->with('success', 'Berhasil! Alat telah dikembalikan beserta bukti foto dan notifikasi email terkirim.');
            } catch (\Exception $mailException) {
                return redirect()->route('pegawai.riwayat.index')->with('warning', 'Berhasil! Alat telah dikembalikan beserta bukti foto, NAMUN email notifikasi gagal dikirim (cek koneksi/SMTP).');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses pengembalian: ' . $e->getMessage());
        }
    }
}