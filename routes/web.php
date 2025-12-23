<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\MataPelajaranController;
use App\Http\Controllers\Guru\MateriController;
use App\Http\Controllers\Guru\TugasController as GuruTugasController;
use App\Http\Controllers\Guru\NilaiController as GuruNilaiController;
use App\Http\Controllers\Guru\DashboardController as GuruDashboardController;
use App\Http\Controllers\Guru\KomentarController;
use App\Http\Controllers\Siswa\MateriSiswaController;
use App\Http\Controllers\Siswa\TugasSiswaController;
use App\Http\Controllers\Siswa\DashboardController as SiswaDashboardController;
use App\Http\Controllers\Siswa\NilaiController as SiswaNilaiController; // TAMBAHKAN INI
use App\Http\Controllers\Siswa\NilaiSiswaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Public routes (hanya login)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

// Protected routes (harus login)
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard routes dengan proteksi role
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard')->middleware('role:admin');

    // Dashboard Guru dengan Controller
    Route::get('/guru/dashboard', [GuruDashboardController::class, 'index'])
        ->name('guru.dashboard')
        ->middleware('role:guru');

    // Dashboard Siswa dengan Controller
    Route::get('/siswa/dashboard', [SiswaDashboardController::class, 'index'])
        ->name('siswa.dashboard')
        ->middleware('role:siswa');

    // Admin management routes - hanya bisa diakses admin
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        // Manajemen Siswa
        Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa.index');
        Route::get('/siswa/create', [SiswaController::class, 'create'])->name('siswa.create');
        Route::post('/siswa', [SiswaController::class, 'store'])->name('siswa.store');
        Route::get('/siswa/{siswa}/edit', [SiswaController::class, 'edit'])->name('siswa.edit');
        Route::put('/siswa/{siswa}', [SiswaController::class, 'update'])->name('siswa.update');
        Route::delete('/siswa/{siswa}', [SiswaController::class, 'destroy'])->name('siswa.destroy');

        // Manajemen Guru
        Route::get('/guru', [GuruController::class, 'index'])->name('guru.index');
        Route::get('/guru/create', [GuruController::class, 'create'])->name('guru.create');
        Route::post('/guru', [GuruController::class, 'store'])->name('guru.store');
        Route::get('/guru/{guru}/edit', [GuruController::class, 'edit'])->name('guru.edit');
        Route::put('/guru/{guru}', [GuruController::class, 'update'])->name('guru.update');
        Route::delete('/guru/{guru}', [GuruController::class, 'destroy'])->name('guru.destroy');
        Route::post('/guru/{guru}/reset-password', [GuruController::class, 'resetPassword'])->name('guru.reset-password');

        // Manajemen Kelas
        Route::resource('kelas', KelasController::class)->except(['show']);

        // Manajemen Mata Pelajaran
        Route::resource('mapel', MataPelajaranController::class)->except(['show']);
    });

    // Guru management routes - hanya bisa diakses guru
    Route::prefix('guru')->name('guru.')->middleware('role:guru')->group(function () {
        // Materi Pembelajaran
        Route::resource('materi', MateriController::class);
        Route::post('/materi/{materi}/komentar', [MateriController::class, 'storeKomentar'])->name('materi.komentar.store');
        Route::delete('/komentar/{komentar}', [MateriController::class, 'destroyKomentar'])->name('materi.komentar.destroy');

        // Tugas untuk Guru
        Route::resource('tugas', GuruTugasController::class);
        Route::post('/tugas/{tugas}/komentar', [GuruTugasController::class, 'storeKomentar'])->name('tugas.komentar.store');
        Route::delete('/tugas/komentar/{komentar}', [GuruTugasController::class, 'destroyKomentar'])->name('tugas.komentar.destroy');
        Route::get('/tugas/{tugas}/pengumpulan', [GuruTugasController::class, 'pengumpulan'])->name('tugas.pengumpulan');
        Route::post('/tugas/{tugas}/nilai', [GuruTugasController::class, 'beriNilai'])->name('tugas.nilai');

        // Route untuk menghapus pengumpulan tugas
        Route::delete('/pengumpulan/{pengumpulan}', [GuruTugasController::class, 'hapusPengumpulan'])->name('tugas.hapus-pengumpulan');

        // Nilai Siswa (UNTUK GURU)
        Route::get('/nilai', [GuruNilaiController::class, 'index'])->name('nilai.index');
        Route::get('/nilai/{siswa}', [GuruNilaiController::class, 'show'])->name('nilai.show');
        Route::post('/nilai/{siswa}', [GuruNilaiController::class, 'store'])->name('nilai.store');
        Route::put('/nilai/{nilai}', [GuruNilaiController::class, 'update'])->name('nilai.update');
        Route::delete('/nilai/{nilai}', [GuruNilaiController::class, 'destroy'])->name('nilai.destroy');

        // Komentar Management
        Route::get('/komentar', [KomentarController::class, 'index'])->name('komentar.index');
        Route::post('/komentar/{id}/reply', [KomentarController::class, 'reply'])->name('komentar.reply');
        Route::post('/komentar/{id}/mark-replied', [KomentarController::class, 'markAsReplied'])->name('komentar.mark-replied');
    });

    // Siswa management routes - hanya bisa diakses siswa
    Route::prefix('siswa')->name('siswa.')->middleware('role:siswa')->group(function () {
        // Dashboard sudah ada di atas

        // Materi untuk Siswa
        Route::get('/materi', [MateriSiswaController::class, 'index'])->name('materi.index');
        Route::get('/materi/{materi}', [MateriSiswaController::class, 'show'])->name('materi.show');
        Route::get('/materi/{materi}/download', [MateriSiswaController::class, 'download'])->name('materi.download');

        // Komentar Materi untuk Siswa
        Route::post('/materi/{materi}/komentar', [MateriSiswaController::class, 'storeKomentar'])->name('materi.komentar.store');
        Route::delete('/komentar/{komentar}', [MateriSiswaController::class, 'destroyKomentar'])->name('materi.komentar.destroy');

        // TUGAS untuk Siswa
        Route::get('/tugas', [TugasSiswaController::class, 'index'])->name('tugas.index');
        Route::get('/tugas/{tugas}', [TugasSiswaController::class, 'show'])->name('tugas.show');

        // Komentar Tugas untuk Siswa
        Route::post('/tugas/{tugas}/komentar', [TugasSiswaController::class, 'storeKomentar'])->name('tugas.komentar.store');
        Route::delete('/komentar/{komentar}', [TugasSiswaController::class, 'destroyKomentar'])->name('tugas.komentar.destroy');

        // Pengumpulan Tugas
        Route::post('/tugas/{tugas}/submit', [TugasSiswaController::class, 'submitTugas'])->name('tugas.submit');

        // Download file tugas yang sudah dikumpulkan
        Route::get('/pengumpulan/{pengumpulan}/download', [TugasSiswaController::class, 'downloadTugas'])->name('tugas.download');

        // NILAI untuk SISWA (ROUTE BARU)
        Route::get('/nilai', [NilaiSiswaController::class, 'index'])->name('nilai.index');
        Route::get('/nilai/{id}', [NilaiSiswaController::class, 'show'])->name('nilai.show');
        Route::get('/nilai-chart', [NilaiSiswaController::class, 'chart'])->name('nilai.chart');
    });

    // Route untuk semua role (optional)
    Route::middleware(['auth'])->group(function () {
        // Route untuk profil user (opsional, jika ada)
        // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
});

// Route fallback untuk 404
Route::fallback(function () {
    return view('errors.404');
});