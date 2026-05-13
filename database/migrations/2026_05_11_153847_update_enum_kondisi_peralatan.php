<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Update kolom kondisi di tabel tbl_item_inventaris
        DB::statement("ALTER TABLE tbl_item_inventaris MODIFY COLUMN kondisi ENUM('Baik', 'Rusak Ringan', 'Rusak Berat') DEFAULT 'Baik'");

        // 2. Update kolom kondisi di tabel tbl_detail_peminjaman (agar nanti saat menyimpan riwayat tidak error)
        DB::statement("ALTER TABLE tbl_detail_peminjaman MODIFY COLUMN kondisi_saat_dipinjam ENUM('Baik', 'Rusak Ringan', 'Rusak Berat') DEFAULT 'Baik'");
        DB::statement("ALTER TABLE tbl_detail_peminjaman MODIFY COLUMN kondisi_saat_kembali ENUM('Baik', 'Rusak Ringan', 'Rusak Berat') NULL DEFAULT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke pengaturan awal jika migration di-rollback
        DB::statement("ALTER TABLE tbl_item_inventaris MODIFY COLUMN kondisi ENUM('Baik', 'Rusak') DEFAULT 'Baik'");
        DB::statement("ALTER TABLE tbl_detail_peminjaman MODIFY COLUMN kondisi_saat_dipinjam ENUM('Baik', 'Rusak') DEFAULT 'Baik'");
        DB::statement("ALTER TABLE tbl_detail_peminjaman MODIFY COLUMN kondisi_saat_kembali ENUM('Baik', 'Rusak') NULL DEFAULT NULL");
    }
};