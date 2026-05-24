<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Tambahkan kode ini:
// Command akan berjalan setiap hari pada jam 08:00 pagi
Schedule::command('logistik:check-overdue')->dailyAt('08:00');

// Command untuk pengingat pengembalian H-1 berjalan setiap jam 08:30 pagi
Schedule::command('peminjaman:pengingat')->dailyAt('08:30');

// (Opsional) Jika ingin jalan setiap jam untuk testing, gunakan:
// Schedule::command('logistik:check-overdue')->hourly();