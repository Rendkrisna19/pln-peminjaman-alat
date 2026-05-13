<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\ItemInventaris;
use App\Models\TrackingLog;
use Illuminate\Http\Request;

class JejakLokasiController extends Controller
{
    // Menampilkan daftar alat untuk dicari barcode-nya
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = ItemInventaris::with('peralatan.rak');

        if ($search) {
            $query->where('kode_barcode', 'like', "%{$search}%")
                  ->orWhereHas('peralatan', function($q) use ($search) {
                      $q->where('nama_alat', 'like', "%{$search}%");
                  });
        }

        $items = $query->paginate(12)->withQueryString();

        return view('supervisor.jejak_lokasi.index', compact('items', 'search'));
    }

    // Menampilkan Timeline/Jejak pergerakan spesifik 1 Barcode
    public function show($id)
    {
        // Ingat relasi peralatan.rak seperti yang kita perbaiki sebelumnya
        $item = ItemInventaris::with('peralatan.rak')->findOrFail($id);
        
        $logs = TrackingLog::with(['user', 'unit_lokasi', 'peminjaman'])
                    ->where('item_inventaris_id', $id)
                    ->orderBy('tanggal_waktu', 'desc') // Urutkan dari yang terbaru
                    ->get();

        return view('supervisor.jejak_lokasi.show', compact('item', 'logs'));
    }
}