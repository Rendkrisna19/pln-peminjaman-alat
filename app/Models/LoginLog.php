<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoginLog extends Model
{
    use HasFactory;

    // 1. Pastikan nama tabelnya sesuai dengan migration (menggunakan tbl_)
    protected $table = 'tbl_login_logs';

    // 2. Izinkan pengisian massal
    protected $guarded = [];

    /**
     * Relasi ke User
     * Setiap log login dimiliki oleh satu User
     */
    public function user(): BelongsTo
    {
        // Parameter kedua adalah foreign key (user_id)
        // Parameter ketiga adalah owner key (id) di tabel tbl_users
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}