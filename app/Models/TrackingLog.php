<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrackingLog extends Model
{
    protected $table = 'tbl_tracking_log';
    protected $guarded = [];

    public function item_inventaris() { return $this->belongsTo(ItemInventaris::class, 'item_inventaris_id'); }
    public function user() { return $this->belongsTo(User::class, 'user_id'); }
    public function unit_lokasi() { return $this->belongsTo(UnitLokasi::class, 'unit_lokasi_id'); }
    public function peminjaman() { return $this->belongsTo(Peminjaman::class, 'peminjaman_id'); }
}