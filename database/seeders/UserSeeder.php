<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Admin (Staf Perlengkapan)
        User::create([
            'nama_lengkap' => 'Admin Perlengkapan',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'no_telepon' => '081234567890',
        ]);

        // 2. Akun Pegawai (Teknisi Lapangan)
        User::create([
            'nama_lengkap' => 'Teknisi Lapangan 1',
            'email' => 'teknisi@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'pegawai',
            'no_telepon' => '081298765432',
        ]);

        // 3. Akun Supervisor (Manajer/Pimpinan)
        User::create([
            'nama_lengkap' => 'Supervisor UP Pandan',
            'email' => 'supervisor@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'supervisor',
            'no_telepon' => '081211223344',
        ]);
    }
}