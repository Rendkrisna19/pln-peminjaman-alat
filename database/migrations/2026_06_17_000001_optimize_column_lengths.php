<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Mengoptimalkan panjang VARCHAR agar tidak boros storage.
     */
    public function up(): void
    {
        // --- tbl_users ---
        DB::statement("ALTER TABLE tbl_users MODIFY COLUMN nama_lengkap VARCHAR(100) NOT NULL");
        DB::statement("ALTER TABLE tbl_users MODIFY COLUMN email VARCHAR(100) NOT NULL");
        // password tetap 255 (bcrypt hash bisa panjang)
        DB::statement("ALTER TABLE tbl_users MODIFY COLUMN no_telepon VARCHAR(20) NULL");
        DB::statement("ALTER TABLE tbl_users MODIFY COLUMN foto_profil VARCHAR(255) NULL");

        // --- tbl_rak_penyimpanan ---
        DB::statement("ALTER TABLE tbl_rak_penyimpanan MODIFY COLUMN nama_rak VARCHAR(50) NOT NULL");
        DB::statement("ALTER TABLE tbl_rak_penyimpanan MODIFY COLUMN lokasi_rak VARCHAR(100) NULL");

        // --- tbl_unit_lokasi ---
        DB::statement("ALTER TABLE tbl_unit_lokasi MODIFY COLUMN nama_unit VARCHAR(100) NOT NULL");
        DB::statement("ALTER TABLE tbl_unit_lokasi MODIFY COLUMN jenis_unit VARCHAR(50) NULL");

        // --- tbl_peralatan ---
        DB::statement("ALTER TABLE tbl_peralatan MODIFY COLUMN nama_alat VARCHAR(100) NOT NULL");
        DB::statement("ALTER TABLE tbl_peralatan MODIFY COLUMN foto VARCHAR(255) NULL");

        // --- tbl_item_inventaris ---
        DB::statement("ALTER TABLE tbl_item_inventaris MODIFY COLUMN kode_barcode VARCHAR(50) NOT NULL");

        // --- tbl_peminjaman ---
        DB::statement("ALTER TABLE tbl_peminjaman MODIFY COLUMN kode_peminjaman VARCHAR(30) NOT NULL");
        DB::statement("ALTER TABLE tbl_peminjaman MODIFY COLUMN foto_pengembalian VARCHAR(255) NULL");

        // --- tbl_detail_peminjaman ---
        // kondisi_saat_dipinjam & kondisi_saat_kembali sudah ENUM, tidak perlu diubah

        // --- tbl_tracking_log ---
        DB::statement("ALTER TABLE tbl_tracking_log MODIFY COLUMN aktivitas VARCHAR(150) NOT NULL");

        // --- tbl_login_logs ---
        DB::statement("ALTER TABLE tbl_login_logs MODIFY COLUMN ip_address VARCHAR(45) NULL");
        DB::statement("ALTER TABLE tbl_login_logs MODIFY COLUMN user_agent VARCHAR(500) NULL");
    }

    /**
     * Reverse the migrations.
     * Kembalikan semua ke VARCHAR(255).
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE tbl_users MODIFY COLUMN nama_lengkap VARCHAR(255) NOT NULL");
        DB::statement("ALTER TABLE tbl_users MODIFY COLUMN email VARCHAR(255) NOT NULL");
        DB::statement("ALTER TABLE tbl_users MODIFY COLUMN no_telepon VARCHAR(255) NULL");
        DB::statement("ALTER TABLE tbl_users MODIFY COLUMN foto_profil VARCHAR(255) NULL");

        DB::statement("ALTER TABLE tbl_rak_penyimpanan MODIFY COLUMN nama_rak VARCHAR(255) NOT NULL");
        DB::statement("ALTER TABLE tbl_rak_penyimpanan MODIFY COLUMN lokasi_rak VARCHAR(255) NULL");

        DB::statement("ALTER TABLE tbl_unit_lokasi MODIFY COLUMN nama_unit VARCHAR(255) NOT NULL");
        DB::statement("ALTER TABLE tbl_unit_lokasi MODIFY COLUMN jenis_unit VARCHAR(255) NULL");

        DB::statement("ALTER TABLE tbl_peralatan MODIFY COLUMN nama_alat VARCHAR(255) NOT NULL");
        DB::statement("ALTER TABLE tbl_peralatan MODIFY COLUMN foto VARCHAR(255) NULL");

        DB::statement("ALTER TABLE tbl_item_inventaris MODIFY COLUMN kode_barcode VARCHAR(255) NOT NULL");

        DB::statement("ALTER TABLE tbl_peminjaman MODIFY COLUMN kode_peminjaman VARCHAR(255) NOT NULL");
        DB::statement("ALTER TABLE tbl_peminjaman MODIFY COLUMN foto_pengembalian VARCHAR(255) NULL");

        DB::statement("ALTER TABLE tbl_tracking_log MODIFY COLUMN aktivitas VARCHAR(255) NOT NULL");

        DB::statement("ALTER TABLE tbl_login_logs MODIFY COLUMN ip_address VARCHAR(255) NULL");
        DB::statement("ALTER TABLE tbl_login_logs MODIFY COLUMN user_agent VARCHAR(255) NULL");
    }
};
