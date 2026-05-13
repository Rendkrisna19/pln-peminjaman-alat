<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peralatan;
use App\Models\RakPenyimpanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PeralatanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $sortField = $request->input('sort_field', 'nama_alat');
        $sortDirection = $request->input('sort_direction', 'asc');

        $query = Peralatan::with('rak'); // Relasi Eager Loading agar ringan

        if ($search) {
            $query->where('nama_alat', 'like', "%{$search}%")
                  ->orWhere('spesifikasi', 'like', "%{$search}%");
        }

        $peralatan = $query->orderBy($sortField, $sortDirection)->paginate(10)->withQueryString();

        return view('admin.peralatan.index', compact('peralatan', 'search', 'sortField', 'sortDirection'));
    }

    public function create()
    {
        $rak = RakPenyimpanan::orderBy('nama_rak', 'asc')->get();
        return view('admin.peralatan.create', compact('rak'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_alat' => 'required|string|max:255',
            'spesifikasi' => 'nullable|string',
            'rak_id' => 'nullable|exists:tbl_rak_penyimpanan,id',
            'total_stok' => 'required|integer|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // Max 2MB
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('foto_peralatan', 'public');
        }

        Peralatan::create($validated);
        return redirect()->route('admin.peralatan.index')->with('success', 'Katalog Peralatan berhasil ditambahkan.');
    }

    public function edit(Peralatan $peralatan)
    {
        $rak = RakPenyimpanan::orderBy('nama_rak', 'asc')->get();
        return view('admin.peralatan.edit', compact('peralatan', 'rak'));
    }

    public function update(Request $request, Peralatan $peralatan)
    {
        $validated = $request->validate([
            'nama_alat' => 'required|string|max:255',
            'spesifikasi' => 'nullable|string',
            'rak_id' => 'nullable|exists:tbl_rak_penyimpanan,id',
            'total_stok' => 'required|integer|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($peralatan->foto) {
                Storage::disk('public')->delete($peralatan->foto);
            }
            $validated['foto'] = $request->file('foto')->store('foto_peralatan', 'public');
        }

        $peralatan->update($validated);
        return redirect()->route('admin.peralatan.index')->with('success', 'Katalog Peralatan berhasil diperbarui.');
    }

    public function destroy(Peralatan $peralatan)
    {
        if ($peralatan->foto) {
            Storage::disk('public')->delete($peralatan->foto);
        }
        $peralatan->delete();
        return redirect()->route('admin.peralatan.index')->with('success', 'Katalog Peralatan berhasil dihapus.');
    }
}