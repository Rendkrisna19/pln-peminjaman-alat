<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'tbl_peminjaman';
    protected $guarded = [];

    public function user() { return $this->belongsTo(User::class, 'user_id'); }
    public function admin() { return $this->belongsTo(User::class, 'admin_id'); }
    public function unit_tujuan() { return $this->belongsTo(UnitLokasi::class, 'unit_tujuan_id'); }
    public function detail_peminjaman() { return $this->hasMany(DetailPeminjaman::class, 'peminjaman_id'); }
}
