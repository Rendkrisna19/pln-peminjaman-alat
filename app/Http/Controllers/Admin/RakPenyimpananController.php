<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RakPenyimpanan;
use Illuminate\Http\Request;

class RakPenyimpananController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $sortField = $request->input('sort_field', 'nama_rak'); // Default urut nama rak
        $sortDirection = $request->input('sort_direction', 'asc');

        $query = RakPenyimpanan::query();

        if ($search) {
            $query->where('nama_rak', 'like', "%{$search}%")
                  ->orWhere('lokasi_rak', 'like', "%{$search}%");
        }

        $rak_penyimpanan = $query->orderBy($sortField, $sortDirection)->paginate(10)->withQueryString();

        return view('admin.rak.index', compact('rak_penyimpanan', 'search', 'sortField', 'sortDirection'));
    }

    public function create() { return view('admin.rak.create'); }

    public function store(Request $request)
    {
        $request->validate(['nama_rak' => 'required|string|max:255', 'lokasi_rak' => 'nullable|string']);
        RakPenyimpanan::create($request->all());
        return redirect()->route('admin.rak-penyimpanan.index')->with('success', 'Data Rak berhasil ditambahkan.');
    }

    public function edit(RakPenyimpanan $rak_penyimpanan) { return view('admin.rak.edit', compact('rak_penyimpanan')); }

    public function update(Request $request, RakPenyimpanan $rak_penyimpanan)
    {
        $request->validate(['nama_rak' => 'required|string|max:255', 'lokasi_rak' => 'nullable|string']);
        $rak_penyimpanan->update($request->all());
        return redirect()->route('admin.rak-penyimpanan.index')->with('success', 'Data Rak berhasil diperbarui.');
    }

    public function destroy(RakPenyimpanan $rak_penyimpanan)
    {
        $rak_penyimpanan->delete();
        return redirect()->route('admin.rak-penyimpanan.index')->with('success', 'Data Rak berhasil dihapus.');
    }
}