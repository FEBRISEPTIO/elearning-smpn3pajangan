<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Nilai;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Siswa;
use App\Models\Tugas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NilaiController extends Controller
{
    public function index()
    {
        $guru = Auth::user()->guru;

        // Ambil kelas yang diampu
        $kelas = $guru->mapelKelas->map(function ($item) {
            return $item->kelas;
        })->unique();

        // Query siswa dengan filter
        $query = Siswa::whereIn('kelas_id', $kelas->pluck('id'))
            ->with(['nilai.tugas', 'kelas']);

        // Filter berdasarkan kelas
        if (request('kelas_id')) {
            $query->where('kelas_id', request('kelas_id'));
        }

        $siswa = $query->get();

        // Filter berdasarkan status jika ada
        if (request('status')) {
            $siswa = $siswa->filter(function ($s) {
                $rataRata = $s->nilai->avg('nilai') ?? 0;

                switch (request('status')) {
                    case 'tuntas':
                        return $rataRata >= 75;
                    case 'remedial':
                        return $rataRata > 0 && $rataRata < 75;
                    case 'belum':
                        return $s->nilai->count() == 0;
                    default:
                        return true;
                }
            });
        }

        // Definisikan jenis nilai
        $jenis = [
            'tugas' => 'Tugas',
        ];

        return view('guru.nilai.index', compact('kelas', 'siswa', 'jenis'));
    }

    public function byKelas(Request $request, $kelasId)
    {
        $guru = Auth::user()->guru;

        // Validasi bahwa guru mengampu kelas ini
        $validKelas = $guru->mapelKelas->where('kelas_id', $kelasId)->first();
        if (!$validKelas) {
            abort(403, 'Anda tidak mengampu kelas ini.');
        }

        $kelas = Kelas::with('siswas')->findOrFail($kelasId);
        $mapel = $request->get('mapel_id')
            ? MataPelajaran::find($request->get('mapel_id'))
            : null;

        // Query nilai berdasarkan kelas dan mapel (jika dipilih)
        $query = Nilai::whereHas('tugas', function ($q) use ($kelasId, $guru, $mapel) {
            $q->where('kelas_id', $kelasId)
                ->where('guru_id', $guru->id);

            if ($mapel) {
                $q->where('mata_pelajaran_id', $mapel->id);
            }
        })->with(['siswa', 'tugas.mataPelajaran']);

        $nilai = $query->get()->groupBy('siswa_id');

        // Hitung rata-rata per siswa
        $rataRata = [];
        foreach ($nilai as $siswaId => $nilais) {
            $rataRata[$siswaId] = $nilais->avg('nilai');
        }

        return view('guru.nilai.kelas', compact('kelas', 'mapel', 'nilai', 'rataRata'));
    }

    public function create()
    {
        $guru = Auth::user()->guru;
        $kelas = $guru->mapelKelas->map(function ($item) {
            return $item->kelas;
        })->unique();

        $mapels = $guru->mapelKelas->map(function ($item) {
            return $item->mataPelajaran;
        })->unique();

        // Ambil tugas yang belum dinilai semua siswanya
        $tugas = Tugas::where('guru_id', $guru->id)
            ->with(['kelas', 'mataPelajaran'])
            ->get();

        return view('guru.nilai.create', compact('kelas', 'mapels', 'tugas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tugas_id' => 'required|exists:tugas,id',
            'nilai.*' => 'required|numeric|min:0|max:100',
            'siswa_id.*' => 'required|exists:siswas,id',
            'komentar.*' => 'nullable|string',
        ]);

        $guru = Auth::user()->guru;
        $tugas = Tugas::find($request->tugas_id);

        // Pastikan tugas milik guru ini
        if ($tugas->guru_id !== $guru->id) {
            abort(403);
        }

        // Simpan nilai untuk setiap siswa
        foreach ($request->siswa_id as $index => $siswaId) {
            if (isset($request->nilai[$index])) {
                Nilai::updateOrCreate(
                    [
                        'tugas_id' => $tugas->id,
                        'siswa_id' => $siswaId,
                    ],
                    [
                        'nilai' => $request->nilai[$index],
                        'komentar' => $request->komentar[$index] ?? null,
                        'guru_id' => $guru->id,
                    ]
                );

                // Update status pengumpulan
                DB::table('pengumpulan_tugas')
                    ->where('tugas_id', $tugas->id)
                    ->where('siswa_id', $siswaId)
                    ->update(['sudah_dinilai' => true]);
            }
        }

        return redirect()->route('guru.nilai.kelas', $tugas->kelas_id)
            ->with('success', 'Nilai berhasil disimpan.');
    }

    public function edit(Nilai $nilai)
    {
        $this->authorize('update', $nilai);

        return view('guru.nilai.edit', compact('nilai'));
    }

    public function update(Request $request, Nilai $nilai)
    {
        $this->authorize('update', $nilai);

        $request->validate([
            'nilai' => 'required|numeric|min:0|max:100',
            'komentar' => 'nullable|string',
        ]);

        $nilai->update($request->only(['nilai', 'komentar']));

        return redirect()->route('guru.nilai.kelas', $nilai->tugas->kelas_id)
            ->with('success', 'Nilai berhasil diperbarui.');
    }

    public function destroy(Nilai $nilai)
    {
        $this->authorize('delete', $nilai);

        $kelasId = $nilai->tugas->kelas_id;
        $nilai->delete();

        return redirect()->route('guru.nilai.kelas', $kelasId)
            ->with('success', 'Nilai berhasil dihapus.');
    }

    public function export(Request $request)
    {
        $guru = Auth::user()->guru;
        $kelasId = $request->get('kelas_id');
        $mapelId = $request->get('mapel_id');

        // Query untuk export
        $query = Nilai::whereHas('tugas', function ($q) use ($kelasId, $mapelId, $guru) {
            $q->where('guru_id', $guru->id);

            if ($kelasId) {
                $q->where('kelas_id', $kelasId);
            }

            if ($mapelId) {
                $q->where('mata_pelajaran_id', $mapelId);
            }
        })->with(['siswa', 'tugas.mataPelajaran', 'tugas.kelas']);

        $nilai = $query->get();

        // Format data untuk export (contoh sederhana)
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="nilai_export.csv"',
        ];

        $callback = function () use ($nilai) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['NIS', 'Nama Siswa', 'Kelas', 'Mata Pelajaran', 'Tugas', 'Nilai', 'Komentar', 'Tanggal']);

            foreach ($nilai as $item) {
                fputcsv($file, [
                    $item->siswa->nis,
                    $item->siswa->nama,
                    $item->tugas->kelas->nama_kelas,
                    $item->tugas->mataPelajaran->nama_mapel,
                    $item->tugas->judul,
                    $item->nilai,
                    $item->komentar,
                    $item->created_at->format('Y-m-d'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}