<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PengumpulanTugas;
use App\Models\Siswa;
use App\Models\Tugas;
use App\Models\Nilai; // Jika ada model Nilai terpisah
use Illuminate\Support\Facades\Auth;

class NilaiSiswaController extends Controller
{
    /**
     * Menampilkan daftar nilai siswa
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $siswa = $user->siswa;

        if (!$siswa) {
            abort(403, 'Data siswa tidak ditemukan');
        }

        // Ambil semua pengumpulan tugas yang sudah dinilai
        $pengumpulan = PengumpulanTugas::with(['tugas.mataPelajaran', 'tugas.guru'])
            ->where('nisn', $siswa->nisn)
            ->where('status', 'graded')
            ->orderBy('updated_at', 'desc')
            ->paginate(15);

        // Hitung statistik
        $totalNilai = $pengumpulan->count();
        $rataRata = $pengumpulan->avg('nilai');
        $nilaiTertinggi = $pengumpulan->max('nilai');
        $nilaiTerendah = $pengumpulan->min('nilai');

        // Filter berdasarkan mata pelajaran
        if ($request->has('mapel') && $request->mapel != '') {
            $pengumpulan = PengumpulanTugas::with(['tugas.mataPelajaran', 'tugas.guru'])
                ->where('nisn', $siswa->nisn)
                ->where('status', 'graded')
                ->whereHas('tugas', function ($query) use ($request) {
                    $query->where('mapel_id', $request->mapel);
                })
                ->orderBy('updated_at', 'desc')
                ->paginate(15);
        }

        // Ambil mata pelajaran unik untuk filter
        $mapels = PengumpulanTugas::where('nisn', $siswa->nisn)
            ->where('status', 'graded')
            ->with('tugas.mataPelajaran')
            ->get()
            ->pluck('tugas.mataPelajaran')
            ->unique()
            ->filter()
            ->values();

        return view('siswa.nilai.index', compact(
            'pengumpulan',
            'siswa',
            'totalNilai',
            'rataRata',
            'nilaiTertinggi',
            'nilaiTerendah',
            'mapels'
        ));
    }

    /**
     * Menampilkan detail nilai
     */
    public function show($id)
    {
        $user = Auth::user();
        $siswa = $user->siswa;

        if (!$siswa) {
            abort(403, 'Data siswa tidak ditemukan');
        }

        $pengumpulan = PengumpulanTugas::with(['tugas.mataPelajaran', 'tugas.guru', 'tugas.kelas'])
            ->where('nisn', $siswa->nisn)
            ->where('id', $id)
            ->where('status', 'graded')
            ->firstOrFail();

        return view('siswa.nilai.show', compact('pengumpulan'));
    }

    /**
     * Menampilkan chart nilai
     */
    public function chart()
    {
        $user = Auth::user();
        $siswa = $user->siswa;

        if (!$siswa) {
            abort(403, 'Data siswa tidak ditemukan');
        }

        // Ambil data untuk chart
        $data = PengumpulanTugas::with('tugas.mataPelajaran')
            ->where('nisn', $siswa->nisn)
            ->where('status', 'graded')
            ->whereNotNull('nilai')
            ->orderBy('updated_at')
            ->get()
            ->groupBy(function ($item) {
                return $item->tugas->mataPelajaran->nama_mapel ?? 'Tanpa Mata Pelajaran';
            });

        $chartData = [];
        foreach ($data as $mapel => $items) {
            $chartData[] = [
                'mapel' => $mapel,
                'rata_rata' => round($items->avg('nilai'), 2),
                'jumlah' => $items->count(),
                'nilai' => $items->pluck('nilai')->toArray()
            ];
        }

        return response()->json($chartData);
    }
}