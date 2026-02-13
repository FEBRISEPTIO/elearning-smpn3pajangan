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

        // Ambil SEMUA nilai (termasuk yang tidak ada tugasnya) untuk statistik
        $allNilai = Nilai::where('nisn', $siswa->nisn);

        // Filter berdasarkan mata pelajaran untuk statistik jika ada filter
        if ($request->filled('mapel')) {
            $allNilai->where('mapel_id', $request->mapel);
        }

        $statNilai = $allNilai->get();

        // Hitung statistik dari semua data
        $totalNilai = $statNilai->count();
        $rataRata = $statNilai->avg('nilai');
        $nilaiTertinggi = $statNilai->max('nilai');
        $nilaiTerendah = $statNilai->min('nilai');

        // Ambil data untuk tabel (menggunakan PengumpulanTugas untuk detail tugas jika ada, 
        // tapi kita bisa pakai Nilai saja agar lebih konsisten mencakup semuanya)
        $query = Nilai::with(['tugas', 'mapel', 'guru', 'pengumpulan' => function ($q) use ($siswa) {
            $q->where('nisn', $siswa->nisn);
        }])
            ->where('nisn', $siswa->nisn)
            ->orderBy('created_at', 'desc');

        if ($request->filled('mapel')) {
            $query->where('mapel_id', $request->mapel);
        }

        $nilais = $query->paginate(15);

        // Ambil mata pelajaran unik untuk filter
        $mapels = Nilai::where('nisn', $siswa->nisn)
            ->with('mapel')
            ->get()
            ->pluck('mapel')
            ->unique('id')
            ->filter()
            ->values();

        // Kita kirim 'nilais' sebagai data utama, tapi agar view tidak pecah (karena pakai $pengumpulan), 
        // kita bisa tetap beri nama $pengumpulan atau update view.
        // Update view lebih bersih. Tapi untuk minimal change, kita aliaskan.
        $pengumpulan = $nilais;

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

        // Cari record Nilai terlebih dahulu
        $nilai = Nilai::with(['tugas.mataPelajaran', 'tugas.guru', 'tugas.kelas', 'mapel', 'guru'])
            ->where('nisn', $siswa->nisn)
            ->where('id', $id)
            ->firstOrFail();

        // Cari pengumpulan terkait jika ada tugas_id
        $pengumpulan = null;
        if ($nilai->tugas_id) {
            $pengumpulan = PengumpulanTugas::with(['tugas.mataPelajaran', 'tugas.guru', 'tugas.kelas'])
                ->where('nisn', $siswa->nisn)
                ->where('tugas_id', $nilai->tugas_id)
                ->first();
        }

        return view('siswa.nilai.show', compact('nilai', 'pengumpulan'));
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
