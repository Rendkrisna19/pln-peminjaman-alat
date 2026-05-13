<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SettingsController;


//import controller admin
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\RakPenyimpananController;
use App\Http\Controllers\Admin\UnitLokasiController;
use App\Http\Controllers\Admin\PeralatanController;
use App\Http\Controllers\Admin\ItemInventarisController;
use App\Http\Controllers\Admin\PeminjamanController;
use App\Http\Controllers\Admin\TrackingController;
use App\Http\Controllers\Admin\UserController;



//import controller pegawai
use App\Http\Controllers\Pegawai\DashboardController as PegawaiDashboard;
use App\Http\Controllers\Pegawai\KatalogController;
use App\Http\Controllers\Pegawai\RiwayatController;
use App\Http\Controllers\Pegawai\PengembalianController;

//import controller supervisor
use App\Http\Controllers\Supervisor\DashboardController as SupervisorDashboard;
use App\Http\Controllers\Supervisor\MonitoringController as SupervisorMonitoring;
use App\Http\Controllers\Supervisor\LaporanController as SupervisorLaporan;
use App\Http\Controllers\Supervisor\RekapController;

// Rute Login Manual
Route::redirect('/', '/login');

// 2. Rute Autentikasi
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login-proses', [AuthController::class, 'authenticate'])->name('login.proses');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// Rute yang butuh Login (Grup Middleware Auth bawaan Laravel)
Route::middleware(['auth'])->group(function () {
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings/update', [SettingsController::class, 'update'])->name('settings.update');


    // Rute KHUSUS ADMIN (Hanya admin yang bisa masuk)
    Route::middleware(['role:admin'])->name('admin.')->prefix('admin')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::resource('rak-penyimpanan', RakPenyimpananController::class);
        Route::resource('unit-lokasi', UnitLokasiController::class);
        Route::resource('peralatan', PeralatanController::class);
        Route::resource('item-inventaris', ItemInventarisController::class);

        Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
        Route::get('/peminjaman/{peminjaman}', [PeminjamanController::class, 'show'])->name('peminjaman.show');
        Route::post('/peminjaman/{peminjaman}/verifikasi', [PeminjamanController::class, 'verifikasi'])->name('peminjaman.verifikasi');
        Route::post('/peminjaman/{peminjaman}/pengembalian', [PeminjamanController::class, 'prosesPengembalian'])->name('peminjaman.pengembalian');
        Route::get('/tracking', [TrackingController::class, 'index'])->name('tracking.index');
        Route::get('/tracking/history/{item_inventaris_id}', [TrackingController::class, 'history'])->name('tracking.history');
        Route::resource('users', UserController::class);
    });

    // Rute KHUSUS PEGAWAI / TEKNISI
    Route::middleware(['auth', 'role:pegawai'])->name('pegawai.')->prefix('pegawai')->group(function () {
        Route::get('/dashboard', [PegawaiDashboard::class, 'index'])->name('dashboard');

        // Katalog & Peminjaman Langsung
        Route::get('/katalog', [KatalogController::class, 'index'])->name('katalog.index');
        Route::get('/katalog/{id}/pinjam', [KatalogController::class, 'formPeminjaman'])->name('katalog.form');
        Route::post('/katalog/{id}/pinjam', [KatalogController::class, 'prosesPeminjaman'])->name('katalog.proses');

        // Riwayat
        Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');
        Route::get('/riwayat/{id}', [RiwayatController::class, 'show'])->name('riwayat.show');

        Route::get('/pengembalian/{id}', [PengembalianController::class, 'form'])->name('pengembalian.form');
        Route::post('/pengembalian/{id}', [PengembalianController::class, 'proses'])->name('pengembalian.proses');
    });
    // Rute KHUSUS SUPERVISOR
    Route::middleware(['role:supervisor'])->name('supervisor.')->prefix('supervisor')->group(function () {
        Route::get('/dashboard', [SupervisorDashboard::class, 'index'])->name('dashboard');
        Route::get('/monitoring', [SupervisorMonitoring::class, 'index'])->name('monitoring.index');
        Route::get('/laporan/peminjaman', [SupervisorLaporan::class, 'peminjaman'])->name('laporan.peminjaman');
        Route::get('/laporan/aset', [SupervisorLaporan::class, 'aset'])->name('laporan.aset');
        Route::get('/rekap', [RekapController::class, 'index'])->name('rekap.index');
        Route::get('/rekap/pdf', [RekapController::class, 'exportPdf'])->name('rekap.pdf');
        Route::get('/rekap/excel', [RekapController::class, 'exportExcel'])->name('rekap.excel');
        Route::get('/laporan/tracking', [SupervisorLaporan::class, 'tracking'])->name('laporan.tracking');
        Route::get('/laporan-aset', [App\Http\Controllers\Supervisor\LaporanAsetController::class, 'index'])->name('laporan.aset');
        Route::get('/laporan-aset/pdf', [App\Http\Controllers\Supervisor\LaporanAsetController::class, 'exportPdf'])->name('laporan.aset.pdf');
        Route::get('/laporan-aset/excel', [App\Http\Controllers\Supervisor\LaporanAsetController::class, 'exportExcel'])->name('laporan.aset.excel');
        Route::get('/jejak-lokasi', [App\Http\Controllers\Supervisor\JejakLokasiController::class, 'index'])->name('jejak.index');
        Route::get('/jejak-lokasi/{id}', [App\Http\Controllers\Supervisor\JejakLokasiController::class, 'show'])->name('jejak.show');
    });

    // Rute GABUNGAN (Misal: Admin dan Supervisor bisa melihat tracking alat)
    Route::middleware(['role:admin,supervisor'])->group(function () {
        Route::get('/tracking/live', function () {
            return 'Halaman Live Tracking Alat (Bisa direfresh pakai AJAX/JS agar realtime tanpa queue)';
        })->name('tracking.live');
    });
});
