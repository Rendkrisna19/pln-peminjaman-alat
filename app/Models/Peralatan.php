<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Peralatan extends Model
{
    // Nama tabel di database
    protected $table = 'tbl_peralatan';
    
    // Izinkan pengisian massal
    protected $guarded = [];

    /**
     * Relasi ke Item Inventaris (Barcode Fisik)
     * Satu Katalog Alat memiliki banyak unit fisik (Item)
     */
    public function item_inventaris(): HasMany
    {
        // Parameter kedua adalah foreign key di tabel tbl_item_inventaris
        return $this->hasMany(ItemInventaris::class, 'peralatan_id');
    }

    /**
     * Relasi ke Rak Penyimpanan
     */
    public function rak(): BelongsTo
    {
        return $this->belongsTo(RakPenyimpanan::class, 'rak_id');
    }
}