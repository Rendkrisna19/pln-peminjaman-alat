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

        $peralatan = Peralatan::create($validated);

        // Auto generate item fisik berdasarkan total_stok
        if ($peralatan->total_stok > 0) {
            for ($i = 1; $i <= $peralatan->total_stok; $i++) {
                // Generate barcode unik misal: PAND-{id_peralatan}-{urutan}-{random_string_2_char}
                $barcode = 'PAND-' . str_pad($peralatan->id, 3, '0', STR_PAD_LEFT) . '-' . str_pad($i, 3, '0', STR_PAD_LEFT) . strtoupper(substr(uniqid(), -2));
                
                while (\App\Models\ItemInventaris::where('kode_barcode', $barcode)->exists()) {
                    $barcode = 'PAND-' . str_pad($peralatan->id, 3, '0', STR_PAD_LEFT) . '-' . str_pad($i, 3, '0', STR_PAD_LEFT) . strtoupper(substr(uniqid(), -2));
                }

                \App\Models\ItemInventaris::create([
                    'peralatan_id' => $peralatan->id,
                    'kode_barcode' => $barcode,
                    'status_ketersediaan' => 'Tersedia',
                    'kondisi' => 'Baik',
                ]);
            }
        }

        return redirect()->route('admin.peralatan.index')->with('success', 'Katalog Peralatan beserta item fisiknya berhasil ditambahkan.');
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

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $newTotalStok = (int) $validated['total_stok'];
            $currentItemsCount = $peralatan->item_inventaris()->count();

            if ($newTotalStok < $currentItemsCount) {
                $diff = $currentItemsCount - $newTotalStok;
                
                // Cari item yang berstatus 'Tersedia' untuk dihapus, urutkan dari yang terbaru (id desc)
                $availableItems = $peralatan->item_inventaris()
                    ->where('status_ketersediaan', 'Tersedia')
                    ->orderBy('id', 'desc')
                    ->limit($diff)
                    ->get();

                if ($availableItems->count() < $diff) {
                    \Illuminate\Support\Facades\DB::rollBack();
                    return redirect()->back()
                        ->withInput()
                        ->with('error', "Stok tidak dapat dikurangi menjadi {$newTotalStok} karena ada unit yang sedang Dipinjam atau Diperbaiki. Hanya ada {$availableItems->count()} unit berstatus Tersedia yang dapat dikurangi.");
                }

                // Hapus item-item tersebut beserta history detail peminjamannya jika ada
                foreach ($availableItems as $item) {
                    \App\Models\DetailPeminjaman::where('item_inventaris_id', $item->id)->delete();
                    $item->delete();
                }
            } elseif ($newTotalStok > $currentItemsCount) {
                $diff = $newTotalStok - $currentItemsCount;

                // Cari sequence terakhir yang terdaftar dari barcode yang ada
                $existingBarcodes = $peralatan->item_inventaris()->pluck('kode_barcode');
                $maxSequence = 0;
                foreach ($existingBarcodes as $bc) {
                    $parts = explode('-', $bc);
                    if (count($parts) >= 3) {
                        $seqVal = (int) $parts[2];
                        if ($seqVal > $maxSequence) {
                            $maxSequence = $seqVal;
                        }
                    }
                }

                for ($i = 1; $i <= $diff; $i++) {
                    $sequence = $maxSequence + $i;
                    $barcode = 'PAND-' . str_pad($peralatan->id, 3, '0', STR_PAD_LEFT) . '-' . str_pad($sequence, 3, '0', STR_PAD_LEFT) . strtoupper(substr(uniqid(), -2));
                    
                    while (\App\Models\ItemInventaris::where('kode_barcode', $barcode)->exists()) {
                        $barcode = 'PAND-' . str_pad($peralatan->id, 3, '0', STR_PAD_LEFT) . '-' . str_pad($sequence, 3, '0', STR_PAD_LEFT) . strtoupper(substr(uniqid(), -2));
                    }

                    \App\Models\ItemInventaris::create([
                        'peralatan_id' => $peralatan->id,
                        'kode_barcode' => $barcode,
                        'status_ketersediaan' => 'Tersedia',
                        'kondisi' => 'Baik',
                    ]);
                }
            }

            if ($request->hasFile('foto')) {
                // Hapus foto lama jika ada
                if ($peralatan->foto) {
                    Storage::disk('public')->delete($peralatan->foto);
                }
                $validated['foto'] = $request->file('foto')->store('foto_peralatan', 'public');
            }

            $peralatan->update($validated);

            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('admin.peralatan.index')->with('success', 'Katalog Peralatan berhasil diperbarui.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
        }
    }

    public function destroy(Peralatan $peralatan)
    {
        // Ambil semua ID item inventaris yang berelasi dengan peralatan ini
        $itemIds = $peralatan->item_inventaris()->pluck('id');
        
        if ($itemIds->isNotEmpty()) {
            // Hapus detail peminjaman (history) yang menggunakan item-item ini untuk menghindari error foreign key
            \App\Models\DetailPeminjaman::whereIn('item_inventaris_id', $itemIds)->delete();
            
            // Hapus item inventaris (fisik)
            $peralatan->item_inventaris()->delete();
        }

        if ($peralatan->foto) {
            Storage::disk('public')->delete($peralatan->foto);
        }
        $peralatan->delete();
        return redirect()->route('admin.peralatan.index')->with('success', 'Katalog Peralatan beserta item fisik dan historynya berhasil dihapus paksa.');
    }
}