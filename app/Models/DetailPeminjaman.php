<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPeminjaman extends Model
{
    use HasFactory;

    // Mendefinisikan nama tabel secara eksplisit
    protected $table = 'tbl_detail_peminjaman';

    // Kolom yang diizinkan untuk Mass Assignment
    protected $fillable = [
        'peminjaman_id',
        'item_inventaris_id',
        'kondisi_saat_dipinjam',
        'kondisi_saat_kembali',
        'catatan_kerusakan',
    ];

    /**
     * Relasi ke tabel Peminjaman (Header)
     */
    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'peminjaman_id');
    }

    /**
     * Relasi ke tabel Item Inventaris (Barcode Satuan)
     */
    public function item_inventaris()
    {
        return $this->belongsTo(ItemInventaris::class, 'item_inventaris_id');
    }
}