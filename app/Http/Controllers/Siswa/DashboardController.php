<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use App\Models\Tugas;
use App\Models\Komentar;
use App\Models\PengumpulanTugas;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $siswa = $user->siswa;

        if (!$siswa) {
            return view('siswa.dashboard', [
                'siswa' => null,
                'error' => 'Data siswa tidak ditemukan'
            ]);
        }

        $kelasId = $siswa->kelas_id;

        // Hitung statistik
        $totalMateri = Materi::where('kelas_id', $kelasId)->count();
        $totalTugas = Tugas::where('kelas_id', $kelasId)->count();

        $materiBaru = Materi::where('kelas_id', $kelasId)
            ->where('created_at', '>=', now()->subDays(7))
            ->count();

        $aktifitas = Komentar::where('user_id', $user->id)
            ->where('user_type', 'siswa')
            ->where('created_at', '>=', now()->subDays(30))
            ->count();

        // Ambil 6 materi terbaru - PERBAIKAN: gunakan relasi guru
        $materiTerbaru = Materi::where('kelas_id', $kelasId)
            ->with(['mataPelajaran', 'guru']) // PERBAIKAN: guru bukan user.guru
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        // Hitung statistik nilai dari model Nilai
        $totalNilai = \App\Models\Nilai::where('nisn', $siswa->nisn)->count();
        $rataNilai = \App\Models\Nilai::where('nisn', $siswa->nisn)->avg('nilai');

        // Ambil 5 nilai terbaru
        $nilaiTerbaru = \App\Models\Nilai::where('nisn', $siswa->nisn)
            ->with(['mapel', 'tugas', 'guru'])
            ->latest()
            ->limit(5)
            ->get();

        // Pass ke view
        return view('siswa.dashboard', compact(
            'siswa',
            'totalMateri',
            'totalTugas',
            'materiBaru',
            'aktifitas',
            'materiTerbaru',
            'totalNilai',
            'rataNilai',
            'nilaiTerbaru'
        ));
    }
}
