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

        // Ambil semua kelas untuk filter (matches create method behavior)
        $kelas = Kelas::orderBy('kelas', 'asc')->get();

        // Query nilai-nilai yang diberikan oleh guru ini
        $query = Nilai::where('guru_nip', $guru->nip)
            ->with(['siswa.kelas', 'tugas', 'mapel']);

        // Filter berdasarkan kelas
        if (request('kelas_id')) {
            $query->whereHas('siswa', function ($q) {
                $q->where('kelas_id', request('kelas_id'));
            });
        }

        // Filter berdasarkan mapel
        if (request('mapel_id')) {
            $query->where('mapel_id', request('mapel_id'));
        }

        $nilais = $query->latest()->get();

        // Ambil semua mata pelajaran untuk filter (matches create method behavior)
        $mapels = MataPelajaran::orderBy('nama_mapel', 'asc')->get();

        return view('guru.nilai.index', compact('kelas', 'nilais', 'mapels'));
    }

    public function create()
    {
        $guru = Auth::user()->guru;

        // Fetch all classes and subjects as fallback if mapel_kelas is empty
        $kelas = Kelas::orderBy('kelas')->get();
        $mapels = MataPelajaran::orderBy('nama_mapel')->get();

        // Still fetch maps but prioritize general options as requested
        $mapelKelas = $guru->mapelKelas()->with(['kelas', 'mataPelajaran'])->get();

        // If he has specific assignments, we could filter but user asked NOT to use mapel kelas primarily
        // So we just provide all options for now to solve the visibility issue.

        // Ambil tugas milik guru ini
        $tugas = Tugas::where('guru_nip', $guru->nip)->get();

        return view('guru.nilai.create', compact('kelas', 'mapels', 'tugas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mapel_id' => 'required|exists:mata_pelajaran,id',
            'tugas_id' => 'nullable|exists:tugas,id',
            'nisn.*' => 'required|exists:siswa,nisn',
            'nilai.*' => 'required|numeric|min:0|max:100',
            'keterangan.*' => 'nullable|string|max:255',
        ]);

        $guru = Auth::user()->guru;

        foreach ($request->nisn as $index => $nisn) {
            if (isset($request->nilai[$index])) {
                Nilai::updateOrCreate(
                    [
                        'nisn' => $nisn,
                        'guru_nip' => $guru->nip,
                        'mapel_id' => $request->mapel_id,
                        'tugas_id' => $request->tugas_id,
                    ],
                    [
                        'nilai' => $request->nilai[$index],
                        'keterangan' => $request->keterangan[$index] ?? null,
                        'tgl_penilaian' => now(),
                    ]
                );

                // Sinkronisasi dengan PengumpulanTugas jika ada tugas_id
                if ($request->tugas_id) {
                    \App\Models\PengumpulanTugas::where('tugas_id', $request->tugas_id)
                        ->where('nisn', $nisn)
                        ->update([
                            'nilai' => $request->nilai[$index],
                            'feedback' => $request->keterangan[$index] ?? null,
                            'status' => 'graded'
                        ]);
                }
            }
        }

        return redirect()->route('guru.nilai.index')
            ->with('success', 'Nilai berhasil disimpan.');
    }

    public function show(Nilai $nilai)
    {
        $guru = Auth::user()->guru;

        if ($nilai->guru_nip !== $guru->nip) {
            abort(403);
        }

        $siswa = $nilai->siswa;
        $nilais = Nilai::where('nisn', $siswa->nisn)
            ->where('guru_nip', $guru->nip)
            ->with(['mapel', 'tugas'])
            ->latest()
            ->get();

        $rataRata = $nilais->avg('nilai');

        return view('guru.nilai.show', compact('siswa', 'nilais', 'rataRata'));
    }

    public function edit(Nilai $nilai)
    {
        $guru = Auth::user()->guru;

        if ($nilai->guru_nip !== $guru->nip) {
            abort(403);
        }

        // Fetch all subjects as fallback if mapel_kelas is empty
        $mapels = MataPelajaran::orderBy('nama_mapel')->get();

        // Still fetch maps for context if needed, but we provide all as requested for visibility
        $mapelKelas = $guru->mapelKelas()->with(['mataPelajaran'])->get();

        return view('guru.nilai.edit', compact('nilai', 'mapels'));
    }

    public function update(Request $request, Nilai $nilai)
    {
        $guru = Auth::user()->guru;

        if ($nilai->guru_nip !== $guru->nip) {
            abort(403);
        }

        $request->validate([
            'mapel_id' => 'required|exists:mata_pelajaran,id',
            'nilai' => 'required|numeric|min:0|max:100',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $nilai->update([
            'mapel_id' => $request->mapel_id,
            'nilai' => $request->nilai,
            'keterangan' => $request->keterangan,
        ]);

        // Sinkronisasi dengan PengumpulanTugas jika ada tugas_id
        if ($nilai->tugas_id) {
            \App\Models\PengumpulanTugas::where('tugas_id', $nilai->tugas_id)
                ->where('nisn', $nilai->nisn)
                ->update([
                    'nilai' => $request->nilai,
                    'feedback' => $request->keterangan,
                    'status' => 'graded'
                ]);
        }

        return redirect()->route('guru.nilai.index')
            ->with('success', 'Nilai berhasil diperbarui.');
    }

    public function destroy(Nilai $nilai)
    {
        $guru = Auth::user()->guru;

        if ($nilai->guru_nip !== $guru->nip) {
            abort(403);
        }

        $nilai->delete();

        return redirect()->route('guru.nilai.index')
            ->with('success', 'Nilai berhasil dihapus.');
    }

    /**
     * Get students by class for batch grading via AJAX
     * If tugasId is provided, only return students who submitted the task
     */
    public function getSiswaByKelas($kelasId, Request $request)
    {
        $tugasId = $request->tugas_id;

        // Jika tidak ada tugas_id, jangan tampilkan siapa-siapa (karena fitur ini untuk bulk grade submission)
        if (!$tugasId) {
            return response()->json([]);
        }

        $query = Siswa::where('kelas_id', $kelasId);

        $query->whereHas('pengumpulanTugas', function ($q) use ($tugasId) {
            $q->where('tugas_id', $tugasId);
        });

        $siswa = $query->orderBy('nama')->get();
        return response()->json($siswa);
    }
}
