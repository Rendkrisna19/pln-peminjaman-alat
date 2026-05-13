<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrackingLog;
use App\Models\ItemInventaris;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $sortDirection = $request->input('sort_direction', 'desc');
        $filterDate = $request->input('filter_date');
        $filterMonth = $request->input('filter_month'); // Format YYYY-MM
        
        // Ambil request per_page, default ke 15 baris jika kosong
        $perPage = $request->input('per_page', 15); 

        $query = TrackingLog::with(['item_inventaris.peralatan', 'user', 'unit_lokasi']);

        // 1. Filter Pencarian Teks
        if ($search) {
            $query->whereHas('item_inventaris', function($q) use ($search) {
                $q->where('kode_barcode', 'like', "%{$search}%")
                  ->orWhereHas('peralatan', function($subq) use ($search) {
                      $subq->where('nama_alat', 'like', "%{$search}%");
                  });
            });
        }

        // 2. Filter Berdasarkan Tanggal Spesifik
        if ($filterDate) {
            $query->whereDate('tanggal_waktu', $filterDate);
        } 
        // 3. Filter Berdasarkan Bulan (Jika tanggal spesifik tidak diisi)
        elseif ($filterMonth) {
            $year = date('Y', strtotime($filterMonth));
            $month = date('m', strtotime($filterMonth));
            $query->whereYear('tanggal_waktu', $year)
                  ->whereMonth('tanggal_waktu', $month);
        }

        // Gunakan variable $perPage di paginate()
        $tracking_logs = $query->orderBy('tanggal_waktu', $sortDirection)->paginate($perPage)->withQueryString();

        return view('admin.tracking.index', compact('tracking_logs', 'search', 'sortDirection', 'filterDate', 'filterMonth', 'perPage'));
    }

    public function history($item_id)
    {
        $item = ItemInventaris::with('peralatan.rak')->findOrFail($item_id);
        
        $logs = TrackingLog::with(['user', 'unit_lokasi', 'peminjaman'])
                    ->where('item_inventaris_id', $item_id)
                    ->orderBy('tanggal_waktu', 'desc')
                    ->get();

        return view('admin.tracking.history', compact('item', 'logs'));
    }
}