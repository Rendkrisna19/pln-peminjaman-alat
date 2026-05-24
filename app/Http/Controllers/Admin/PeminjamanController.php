<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use App\Models\ItemInventaris;
use App\Models\TrackingLog;
use App\Mail\NotifikasiPeminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail; 

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $sortField = $request->input('sort_field', 'tanggal_pengajuan');
        $sortDirection = $request->input('sort_direction', 'desc');
        $filterStatus = $request->input('status_peminjaman');

        $query = Peminjaman::with(['user', 'unit_tujuan', 'admin']);

        if ($search) {
            $query->where('kode_peminjaman', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('nama_lengkap', 'like', "%{$search}%");
                  });
        }

        if ($filterStatus) {
            $query->where('status_peminjaman', $filterStatus);
        }

        $peminjaman = $query->orderBy($sortField, $sortDirection)->paginate(10)->withQueryString();

        return view('admin.peminjaman.index', compact('peminjaman', 'search', 'sortField', 'sortDirection', 'filterStatus'));
    }

    public function show(Peminjaman $peminjaman)
    {
        $peminjaman->load(['user', 'unit_tujuan', 'admin', 'detail_peminjaman.item_inventaris.peralatan']);
        return view('admin.peminjaman.show', compact('peminjaman'));
    }

    // Fungsi untuk Menerima/Menolak Permohonan
    public function verifikasi(Request $request, Peminjaman $peminjaman)
    {
        $request->validate([
            'status_peminjaman' => 'required|in:Disetujui,Ditolak',
        ]);

        DB::beginTransaction();
        try {
            $statusBaru = $request->status_peminjaman === 'Disetujui' ? 'Sedang Dipinjam' : 'Ditolak';
            
            $peminjaman->update([
                'status_peminjaman' => $statusBaru,
                'admin_id' => Auth::id(),
            ]);

            if ($request->status_peminjaman === 'Disetujui') {
                $details = DetailPeminjaman::where('peminjaman_id', $peminjaman->id)->get();
                foreach ($details as $detail) {
                    $item = ItemInventaris::find($detail->item_inventaris_id);
                    $item->update(['status_ketersediaan' => 'Dipinjam']);

                    TrackingLog::create([
                        'item_inventaris_id' => $item->id,
                        'peminjaman_id' => $peminjaman->id,
                        'user_id' => $peminjaman->user_id,
                        'unit_lokasi_id' => $peminjaman->unit_tujuan_id,
                        'aktivitas' => 'Alat disetujui dan dipinjam ke ' . $peminjaman->unit_tujuan->nama_unit,
                        'tanggal_waktu' => now(),
                    ]);
                }
            }
            
            DB::commit(); 

            try {
                $peminjaman->load(['user', 'unit_tujuan', 'detail_peminjaman.item_inventaris.peralatan']); 
                $emailPegawai = $peminjaman->user->email;
                $jenisNotif = $request->status_peminjaman === 'Disetujui' ? 'disetujui' : 'ditolak';

                Mail::to($emailPegawai)->send(new NotifikasiPeminjaman($peminjaman, $jenisNotif));
                
                return redirect()->route('admin.peminjaman.index')->with('success', 'Verifikasi berhasil, email notifikasi telah terkirim ke pengguna!');
            } catch (\Exception $mailException) {
                return redirect()->route('admin.peminjaman.index')->with('warning', 'Verifikasi berhasil disimpan, NAMUN email gagal dikirim karena masalah SMTP/Internet.');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan sistem saat memproses verifikasi: ' . $e->getMessage());
        }
    }

    // Fungsi untuk Memproses Pengembalian Alat
    public function prosesPengembalian(Request $request, Peminjaman $peminjaman)
    {
        $request->validate([
            'kondisi_kembali' => 'required|array', 
            'catatan_kerusakan' => 'nullable|array',
            // Dibuat NULLABLE karena bisa saja Pegawai sudah mengupload fotonya duluan
            'foto_pengembalian' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
        ]);

        DB::beginTransaction();
        try {
            // Ambil foto yang sudah ada (jika pegawai sudah mengupload sebelumnya)
            $pathFoto = $peminjaman->foto_pengembalian;

            // Jika Admin mengupload foto baru (override/Admin yang mengembalikan)
            if ($request->hasFile('foto_pengembalian')) {
                $pathFoto = $request->file('foto_pengembalian')->store('bukti_pengembalian', 'public');
            }

            $peminjaman->update([
                'status_peminjaman' => 'Dikembalikan',
                'tanggal_dikembalikan' => now(),
                'foto_pengembalian' => $pathFoto, // Simpan ke nama kolom yang baru
            ]);

            foreach ($request->kondisi_kembali as $detail_id => $kondisi) {
                $detail = DetailPeminjaman::find($detail_id);
                $detail->update([
                    'kondisi_saat_kembali' => $kondisi,
                    'catatan_kerusakan' => $request->catatan_kerusakan[$detail_id] ?? null,
                ]);

                $item = ItemInventaris::find($detail->item_inventaris_id);
                $statusKetersediaan = ($kondisi == 'Rusak Berat') ? 'Diperbaiki' : 'Tersedia';
                
                $item->update([
                    'status_ketersediaan' => $statusKetersediaan,
                    'kondisi' => $kondisi, 
                ]);

                TrackingLog::create([
                    'item_inventaris_id' => $item->id,
                    'peminjaman_id' => $peminjaman->id,
                    'user_id' => Auth::id(), 
                    'unit_lokasi_id' => null, 
                    'aktivitas' => 'Alat dikembalikan ke gudang dengan kondisi: ' . $kondisi,
                    'tanggal_waktu' => now(),
                ]);
            }
            DB::commit();

            try {
                $peminjaman->load(['user']); 
                $emailPegawai = $peminjaman->user->email;
                $emailAdmin = env('MAIL_FROM_ADDRESS', config('mail.from.address'));

                Mail::to($emailPegawai)
                    ->cc($emailAdmin)
                    ->send(new NotifikasiPeminjaman($peminjaman, 'dikembalikan'));
                
                return redirect()->route('admin.peminjaman.index')->with('success', 'Pengembalian alat berhasil diproses dan email terkirim.');
            } catch (\Exception $mailException) {
                return redirect()->route('admin.peminjaman.index')->with('warning', 'Pengembalian alat berhasil diproses, NAMUN email gagal dikirim karena masalah SMTP/Internet.');
            }
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}