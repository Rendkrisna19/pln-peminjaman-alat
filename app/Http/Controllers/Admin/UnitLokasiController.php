<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UnitLokasi;
use Illuminate\Http\Request;

class UnitLokasiController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $sortField = $request->input('sort_field', 'nama_unit'); 
        $sortDirection = $request->input('sort_direction', 'asc');

        $query = UnitLokasi::query();

        if ($search) {
            $query->where('nama_unit', 'like', "%{$search}%")
                  ->orWhere('jenis_unit', 'like', "%{$search}%");
        }

        $unit_lokasi = $query->orderBy($sortField, $sortDirection)->paginate(10)->withQueryString();

        return view('admin.unit.index', compact('unit_lokasi', 'search', 'sortField', 'sortDirection'));
    }

    public function create() { return view('admin.unit.create'); }

    public function store(Request $request)
    {
        $request->validate(['nama_unit' => 'required|string|max:255', 'jenis_unit' => 'nullable|string']);
        UnitLokasi::create($request->all());
        return redirect()->route('admin.unit-lokasi.index')->with('success', 'Data Unit Lokasi berhasil ditambahkan.');
    }

    // MENGGUNAKAN $id MANUAL UNTUK MENGHINDARI ERROR BINDING
    public function edit($id) 
    { 
        $unit_lokasi = UnitLokasi::findOrFail($id);
        return view('admin.unit.edit', compact('unit_lokasi')); 
    }

    public function update(Request $request, $id)
    {
        $unit_lokasi = UnitLokasi::findOrFail($id);
        $request->validate(['nama_unit' => 'required|string|max:255', 'jenis_unit' => 'nullable|string']);
        
        $unit_lokasi->update($request->all());
        return redirect()->route('admin.unit-lokasi.index')->with('success', 'Data Unit Lokasi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $unit_lokasi = UnitLokasi::findOrFail($id);
        $unit_lokasi->delete();
        
        return redirect()->route('admin.unit-lokasi.index')->with('success', 'Data Unit Lokasi berhasil dihapus.');
    }
}