<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\ItemInventaris;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $sortField = $request->input('sort_field', 'kode_barcode');
        $sortDirection = $request->input('sort_direction', 'asc');
        $filterStatus = $request->input('status_ketersediaan');
        $perPage = $request->input('per_page', 10); 

        $query = ItemInventaris::with(['peralatan.rak']);

        if ($search) {
            $query->where('kode_barcode', 'like', "%{$search}%")
                  ->orWhereHas('peralatan', function($q) use ($search) {
                      $q->where('nama_alat', 'like', "%{$search}%");
                  });
        }

        if ($filterStatus) {
            $query->where('status_ketersediaan', $filterStatus);
        }

        $monitoring = $query->orderBy($sortField, $sortDirection)->paginate($perPage)->withQueryString();

        // Fitur No-Reload: Ditambah parameter fallback 'ajax_request' agar lebih kebal
        if ($request->ajax() || $request->has('ajax_request')) {
            return view('supervisor.monitoring._table', compact('monitoring', 'sortField', 'sortDirection'))->render();
        }

        return view('supervisor.monitoring.index', compact('monitoring', 'search', 'sortField', 'sortDirection', 'filterStatus', 'perPage'));
    }
}