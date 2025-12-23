<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Materi;
use App\Models\Tugas;
use App\Models\MapelKelas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $guru = $user->guru;

        if (!$guru) {
            return redirect()->route('login')->with('error', 'Data guru tidak ditemukan.');
        }

        // AMBIL KELAS YANG DIAMPU GURU INI
        $mapelKelas = MapelKelas::where('guru_id', $guru->id)->get();

        // 1. TOTAL MATERI (TIDAK LANGSUNG - melalui mapel_kelas)
        $totalMateri = 0;
        $materiIds = [];

        foreach ($mapelKelas as $mk) {
            // Ambil semua materi untuk mata pelajaran ini
            $materiCount = Materi::where('mapel_id', $mk->mata_pelajaran_id)->count();
            $totalMateri += $materiCount;

            // Simpan ID materi untuk query lainnya
            $materis = Materi::where('mapel_id', $mk->mata_pelajaran_id)->pluck('id')->toArray();
            $materiIds = array_merge($materiIds, $materis);
        }

        // 2. TOTAL TUGAS (asumsi tabel tugas punya kolom mapel_id)
        $totalTugas = 0;

        foreach ($mapelKelas as $mk) {
            // Hitung tugas untuk setiap mata pelajaran
            $tugasCount = Tugas::where('mata_pelajaran_id', $mk->mata_pelajaran_id)->count();
            $totalTugas += $tugasCount;
        }

        // 3. TOTAL SISWA (dari kelas yang diampu)
        $totalSiswa = 0;
        $kelasIds = $mapelKelas->pluck('kelas_id')->unique();

        foreach ($kelasIds as $kelasId) {
            $siswaCount = DB::table('siswa')
                ->where('kelas_id', $kelasId)
                ->count();
            $totalSiswa += $siswaCount;
        }

        // 4. TUGAS BELUM DINILAI (asumsi ada tabel pengumpulan_tugas)
        $tugasBelumDinilai = 0;

        foreach ($mapelKelas as $mk) {
            // Ambil semua tugas untuk mapel ini
            $tugasIds = Tugas::where('mata_pelajaran_id', $mk->mata_pelajaran_id)
                ->pluck('id')
                ->toArray();

            if (!empty($tugasIds)) {
                // Hitung pengumpulan yang belum dinilai
                $belumDinilai = DB::table('pengumpulan_tugas')
                    ->whereIn('tugas_id', $tugasIds)
                    ->where('sudah_dinilai', false)
                    ->count();

                $tugasBelumDinilai += $belumDinilai;
            }
        }

        // 5. MATERI TERBARU (5 terbaru)
        $recentMateri = collect();
        if (!empty($materiIds)) {
            $recentMateri = Materi::whereIn('id', $materiIds)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        }

        // 6. TUGAS TERBARU (5 terbaru)
        $tugasIds = [];
        foreach ($mapelKelas as $mk) {
            $ids = Tugas::where('mata_pelajaran_id', $mk->mata_pelajaran_id)
                ->pluck('id')
                ->toArray();
            $tugasIds = array_merge($tugasIds, $ids);
        }

        $recentTugas = collect();
        if (!empty($tugasIds)) {
            $recentTugas = Tugas::whereIn('id', $tugasIds)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        }

        // 7. TUGAS DEADLINE (dalam 7 hari)
        $tugasDeadline = collect();
        if (!empty($tugasIds)) {
            $tugasDeadline = Tugas::whereIn('id', $tugasIds)
                ->where('deadline', '>=', now())
                ->where('deadline', '<=', now()->addDays(7))
                ->orderBy('deadline', 'asc')
                ->get();
        }

        // Kirim data ke view
        $data = [
            'guru' => $guru,
            'totalMateri' => $totalMateri,
            'totalTugas' => $totalTugas,
            'totalSiswa' => $totalSiswa,
            'tugasBelumDinilai' => $tugasBelumDinilai,
            'recentMateri' => $recentMateri,
            'recentTugas' => $recentTugas,
            'tugasDeadline' => $tugasDeadline,
            'mapelKelas' => $mapelKelas,
            'kelasDiampu' => $kelasIds->count()
        ];

        return view('guru.dashboard', $data);
    }
}