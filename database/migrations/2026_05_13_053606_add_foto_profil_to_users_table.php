<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Ganti 'tbl_users' dengan nama tabel user Anda yang sebenarnya di database
        Schema::table('tbl_users', function (Blueprint $table) {
            // Menambahkan setelah kolom password atau email, bebas
            $table->string('foto_profil')->nullable()->after('password'); 
        });
    }

    public function down()
    {
        // Ganti juga di sini
        Schema::table('tbl_users', function (Blueprint $table) {
            $table->dropColumn('foto_profil');
        });
    }
};
