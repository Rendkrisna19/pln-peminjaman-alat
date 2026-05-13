<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ItemInventaris;
use App\Models\Peralatan;
use Illuminate\Http\Request;

class ItemInventarisController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $sortField = $request->input('sort_field', 'kode_barcode');
        $sortDirection = $request->input('sort_direction', 'asc');
        $filterKondisi = $request->input('kondisi'); 

        $query = ItemInventaris::with('peralatan');

        if ($search) {
            $query->where('kode_barcode', 'like', "%{$search}%")
                  ->orWhereHas('peralatan', function($q) use ($search) {
                      $q->where('nama_alat', 'like', "%{$search}%");
                  });
        }

        if ($filterKondisi) {
            $query->where('kondisi', $filterKondisi);
        }

        $item_inventaris = $query->orderBy($sortField, $sortDirection)->paginate(15)->withQueryString();

        return view('admin.item.index', compact('item_inventaris', 'search', 'sortField', 'sortDirection', 'filterKondisi'));
    }

    public function create()
    {
        $peralatan = Peralatan::orderBy('nama_alat', 'asc')->get();
        return view('admin.item.create', compact('peralatan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'peralatan_id' => 'required|exists:tbl_peralatan,id',
            'kode_barcode' => 'required|string|unique:tbl_item_inventaris,kode_barcode',
            // UPDATE: Menyesuaikan dengan ENUM yang baru
            'kondisi' => 'required|in:Baik,Rusak Ringan,Rusak Berat',
            'status_ketersediaan' => 'required|in:Tersedia,Dipinjam,Diperbaiki',
        ]);

        ItemInventaris::create($request->all());
        return redirect()->route('admin.item-inventaris.index')->with('success', 'Item Barcode berhasil ditambahkan.');
    }
    
    public function edit($id)
    {
        $item_inventaris = ItemInventaris::findOrFail($id);
        $peralatan = Peralatan::orderBy('nama_alat', 'asc')->get();
        
        return view('admin.item.edit', compact('item_inventaris', 'peralatan'));
    }

    public function update(Request $request, $id)
    {
        $item_inventaris = ItemInventaris::findOrFail($id);

        $request->validate([
            'peralatan_id' => 'required|exists:tbl_peralatan,id',
            'kode_barcode' => 'required|string|unique:tbl_item_inventaris,kode_barcode,' . $item_inventaris->id,
            // UPDATE: Menyesuaikan dengan ENUM yang baru
            'kondisi' => 'required|in:Baik,Rusak Ringan,Rusak Berat',
            'status_ketersediaan' => 'required|in:Tersedia,Dipinjam,Diperbaiki',
        ]);

        $item_inventaris->update($request->all());
        return redirect()->route('admin.item-inventaris.index')->with('success', 'Item Barcode berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $item_inventaris = ItemInventaris::findOrFail($id);
        $item_inventaris->delete();
        
        return redirect()->route('admin.item-inventaris.index')->with('success', 'Item Barcode berhasil dihapus.');
    }
}