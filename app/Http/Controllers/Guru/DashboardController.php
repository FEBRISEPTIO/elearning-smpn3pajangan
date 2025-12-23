<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Materi;
use App\Models\Tugas;
use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\Guru;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $guru = auth()->user()->guru;

        // Ambil kelas yang diampu
        $kelasDiampu = $guru->mapelKelas ?? collect();
        $totalSiswa = 0;

        // Hitung total siswa dari semua kelas yang diampu
        foreach ($kelasDiampu as $mapelKelas) {
            if ($mapelKelas->kelas) {
                $totalSiswa += $mapelKelas->kelas->siswas->count();
            }
        }

        $data = [
            'guru' => $guru,
            'totalMateri' => Materi::where('guru_nik', $guru->id)->count(),
            'totalTugas' => Tugas::where('guru_nik', $guru->id)->count(),
            'totalSiswa' => $totalSiswa,
            'recentMateri' => Materi::where('guru_nik', $guru->id)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(),
            'recentTugas' => Tugas::where('guru_nik', $guru->id)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(),
        ];

        return view('guru.dashboard', $data);
    }
}