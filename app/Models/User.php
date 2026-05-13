<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // TAMBAHKAN BARIS INI UNTUK MENYESUAIKAN NAMA TABEL
    protected $table = 'tbl_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_lengkap', // Sesuaikan kolom bawaan jika belum
        'email',
        'password',
        'role',
        'no_telepon',
        'foto_profil', // Tambahkan ini jika sudah ada di migrasi
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function login_logs()
{
    return $this->hasMany(LoginLog::class, 'user_id');
}
}