<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\MataPelajaranController;
use App\Http\Controllers\Admin\DataSekolahController;
use App\Http\Controllers\Guru\MateriController;
use App\Http\Controllers\Guru\TugasController; // GUNAKAN LANGSUNG
use App\Http\Controllers\Guru\NilaiController as GuruNilaiController;
use App\Http\Controllers\Guru\DashboardController as GuruDashboardController;
use App\Http\Controllers\Guru\KomentarController;
use App\Http\Controllers\Siswa\MateriSiswaController;
use App\Http\Controllers\Siswa\TugasSiswaController;
use App\Http\Controllers\Siswa\DashboardController as SiswaDashboardController;
use App\Http\Controllers\Siswa\NilaiSiswaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Public routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/upload-image', [App\Http\Controllers\UploadController::class, 'uploadImage'])->name('upload.image')->middleware('auth');

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard routes
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard')->middleware('role:admin');

    Route::get('/guru/dashboard', [GuruDashboardController::class, 'index'])
        ->name('guru.dashboard')
        ->middleware('role:guru');

    Route::get('/siswa/dashboard', [SiswaDashboardController::class, 'index'])
        ->name('siswa.dashboard')
        ->middleware('role:siswa');

    // Admin management routes
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        // Data Sekolah
        Route::get('/data-sekolah', [DataSekolahController::class, 'index'])->name('data-sekolah.index');
        Route::get('/data-sekolah/edit', [DataSekolahController::class, 'edit'])->name('data-sekolah.edit');
        Route::put('/data-sekolah', [DataSekolahController::class, 'update'])->name('data-sekolah.update');

        // Siswa
        Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa.index');
        Route::get('/siswa/create', [SiswaController::class, 'create'])->name('siswa.create');
        Route::post('/siswa', [SiswaController::class, 'store'])->name('siswa.store');
        Route::get('/siswa/{siswa}/edit', [SiswaController::class, 'edit'])->name('siswa.edit');
        Route::put('/siswa/{siswa}', [SiswaController::class, 'update'])->name('siswa.update');
        Route::delete('/siswa/{siswa}', [SiswaController::class, 'destroy'])->name('siswa.destroy');
        Route::get('/siswa/template', [SiswaController::class, 'downloadTemplate'])->name('siswa.template');
        Route::post('/siswa/import', [SiswaController::class, 'import'])->name('siswa.import');

        // Guru
        Route::get('/guru', [GuruController::class, 'index'])->name('guru.index');
        Route::get('/guru/create', [GuruController::class, 'create'])->name('guru.create');
        Route::post('/guru', [GuruController::class, 'store'])->name('guru.store');
        Route::get('/guru/{nip}/edit', [GuruController::class, 'edit'])->name('guru.edit');
        Route::put('/guru/{nip}', [GuruController::class, 'update'])->name('guru.update');
        Route::delete('/guru/{nip}', [GuruController::class, 'destroy'])->name('guru.destroy');
        Route::post('/guru/{nip}/reset-password', [GuruController::class, 'resetPassword'])->name('guru.reset-password');

        // Kelas
        Route::resource('kelas', KelasController::class)->except(['show']);

        // Mata Pelajaran
        Route::resource('mapel', MataPelajaranController::class)->except(['show']);
    });

    // Guru management routes
    Route::prefix('guru')->name('guru.')->middleware('role:guru')->group(function () {
        // Materi
        Route::resource('materi', MateriController::class);
        Route::post('/materi/{materi}/komentar', [MateriController::class, 'storeKomentar'])->name('materi.komentar.store');
        Route::delete('/komentar/{komentar}', [MateriController::class, 'destroyKomentar'])->name('materi.komentar.destroy');

        // Tugas - GUNAKAN TugasController langsung
        Route::resource('tugas', TugasController::class);
        Route::post('/tugas/{id}/komentar', [TugasController::class, 'storeKomentar'])->name('tugas.komentar.store');
        Route::delete('/tugas/komentar/{komentar}', [TugasController::class, 'destroyKomentar'])->name('tugas.komentar.destroy');
        Route::get('/tugas/{id}/pengumpulan', [TugasController::class, 'pengumpulan'])->name('tugas.pengumpulan');
        Route::post('/tugas/{id}/nilai', [TugasController::class, 'beriNilai'])->name('tugas.nilai');
        Route::delete('/pengumpulan/{pengumpulan}', [TugasController::class, 'hapusPengumpulan'])->name('tugas.hapus-pengumpulan');
        Route::get('/pengumpulan/{pengumpulan}/download', [TugasController::class, 'downloadPengumpulan'])->name('guru.tugas.download-pengumpulan');
        // Nilai
        Route::get('/nilai', [GuruNilaiController::class, 'index'])->name('nilai.index');
        Route::get('/nilai/{siswa}', [GuruNilaiController::class, 'show'])->name('nilai.show');
        Route::post('/nilai/{siswa}', [GuruNilaiController::class, 'store'])->name('nilai.store');
        Route::put('/nilai/{nilai}', [GuruNilaiController::class, 'update'])->name('nilai.update');
        Route::delete('/nilai/{nilai}', [GuruNilaiController::class, 'destroy'])->name('nilai.destroy');

        // Komentar
        Route::get('/komentar', [KomentarController::class, 'index'])->name('komentar.index');
        Route::post('/komentar/{id}/reply', [KomentarController::class, 'reply'])->name('komentar.reply');
        Route::post('/komentar/{id}/mark-replied', [KomentarController::class, 'markAsReplied'])->name('komentar.mark-replied');
    });

    // Siswa management routes
    Route::prefix('siswa')->name('siswa.')->middleware('role:siswa')->group(function () {
        // Materi
        Route::get('/materi', [MateriSiswaController::class, 'index'])->name('materi.index');
        Route::get('/materi/{materi}', [MateriSiswaController::class, 'show'])->name('materi.show');
        Route::get('/materi/{materi}/download', [MateriSiswaController::class, 'download'])->name('materi.download');
        Route::post('/materi/{materi}/komentar', [MateriSiswaController::class, 'storeKomentar'])->name('materi.komentar.store');
        Route::delete('/komentar/{komentar}', [MateriSiswaController::class, 'destroyKomentar'])->name('materi.komentar.destroy');

        // Tugas
        Route::get('/tugas', [TugasSiswaController::class, 'index'])->name('tugas.index');
        Route::get('/tugas/{tugas}', [TugasSiswaController::class, 'show'])->name('tugas.show');
        Route::post('/tugas/{tugas}/komentar', [TugasSiswaController::class, 'storeKomentar'])->name('tugas.komentar.store');
        Route::delete('/komentar/{komentar}', [TugasSiswaController::class, 'destroyKomentar'])->name('tugas.komentar.destroy');
        Route::post('/tugas/{tugas}/submit', [TugasSiswaController::class, 'submitTugas'])->name('tugas.submit');
        Route::get('/pengumpulan/{pengumpulan}/download', [TugasSiswaController::class, 'downloadTugas'])->name('tugas.download');

        // Nilai
        Route::get('/nilai', [NilaiSiswaController::class, 'index'])->name('nilai.index');
        Route::get('/nilai/{id}', [NilaiSiswaController::class, 'show'])->name('nilai.show');
        Route::get('/nilai-chart', [NilaiSiswaController::class, 'chart'])->name('nilai.chart');
    });
});

// Route fallback
Route::fallback(function () {
    return view('errors.404');
});
