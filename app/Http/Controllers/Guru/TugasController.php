<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tugas;
use App\Models\Komentar;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\PengumpulanTugas;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;

class TugasController extends Controller
{
    public function index(Request $request)
    {
        $guru = Auth::user()->guru;

        if (!$guru) {
            abort(403, 'Anda tidak memiliki akses guru');
        }

        $query = Tugas::where('guru_nik', $guru->nik)
            ->with(['kelas', 'mataPelajaran'])
            ->withCount('komentar');

        // Filter
        if ($request->has('kelas') && $request->kelas != '') {
            $query->where('kelas_id', $request->kelas);
        }

        if ($request->has('mapel') && $request->mapel != '') {
            $query->where('mapel_id', $request->mapel);
        }

        if ($request->has('status') && $request->status != '') {
            if ($request->status == 'aktif') {
                $query->where('deadline', '>', now());
            } elseif ($request->status == 'selesai') {
                $query->where('deadline', '<=', now());
            }
        }

        if ($request->has('search') && $request->search != '') {
            $query->where('judul_tugas', 'like', '%' . $request->search . '%');
        }

        $tugas = $query->orderBy('deadline', 'asc')->paginate(10);
        $kelas = Kelas::orderBy('kelas', 'asc')->get();
        $mapels = MataPelajaran::orderBy('nama_mapel', 'asc')->get();

        return view('guru.tugas.index', compact('tugas', 'kelas', 'mapels'));
    }

    public function create()
    {
        $guru = Auth::user()->guru;

        if (!$guru) {
            abort(403, 'Anda tidak memiliki akses guru');
        }

        $kelas = Kelas::orderBy('kelas', 'asc')->get();
        $mapels = MataPelajaran::orderBy('nama_mapel', 'asc')->get();

        return view('guru.tugas.create', compact('kelas', 'mapels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kelas_id' => 'required|exists:kelas,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'deadline' => 'required|date|after:now',
            'konten_tugas' => 'nullable|string',
        ]);

        $guru = Auth::user()->guru;

        if (!$guru) {
            abort(403, 'Anda tidak memiliki akses guru');
        }

        $data = [
            'judul_tugas' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'mapel_id' => $request->mata_pelajaran_id,
            'kelas_id' => $request->kelas_id,
            'guru_nik' => $guru->nik,
            'tugas' => $request->konten_tugas,
            'deadline' => $request->deadline,
        ];

        if (empty($data['tugas'])) {
            $data['tugas'] = 'Tidak ada konten tambahan';
        }

        $tugas = Tugas::create($data);

        return redirect()->route('guru.tugas.index')
            ->with('success', 'Tugas berhasil ditambahkan.');
    }

    public function show($id)
{


    // Cari tugas dengan relasi
    $tugas = Tugas::with(['kelas', 'mataPelajaran'])
        ->withCount('komentar')
        ->find($id);

    if (!$tugas) {
        abort(404, 'Tugas tidak ditemukan');
    }

    // Authorization check - INI YANG TIDAK ADA SEBELUMNYA
    $guru = Auth::user()->guru;
    
    if (!$guru) {
        abort(403, 'Data guru tidak ditemukan');
    }

    if ($guru->nik != $tugas->guru_nik) {
        abort(403, 'ANDA TIDAK MEMILIKI AKSES KE TUGAS INI');
    }

    // Query komentar
    $komentars = Komentar::where('tugas_id', $tugas->id)
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    return view('guru.tugas.show', compact('tugas', 'komentars'));
}

    public function edit(Tugas $tugas)
    {
        if (Auth::user()->guru->nik != $tugas->guru_nik) {
            abort(403, 'Anda tidak memiliki akses ke tugas ini');
        }

        $kelas = Kelas::orderBy('kelas', 'asc')->get();
        $mapels = MataPelajaran::orderBy('nama_mapel', 'asc')->get();

        return view('guru.tugas.edit', compact('tugas', 'kelas', 'mapels'));
    }

    public function update(Request $request, Tugas $tugas)
    {
        if (Auth::user()->guru->nik != $tugas->guru_nik) {
            abort(403, 'Anda tidak memiliki akses ke tugas ini');
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kelas_id' => 'required|exists:kelas,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'deadline' => 'required|date',
            'konten_tugas' => 'nullable|string',
        ]);

        $data = [
            'judul_tugas' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'mapel_id' => $request->mata_pelajaran_id,
            'kelas_id' => $request->kelas_id,
            'tugas' => $request->konten_tugas,
            'deadline' => $request->deadline,
        ];

        $tugas->update($data);

        return redirect()->route('guru.tugas.show', $tugas)
            ->with('success', 'Tugas berhasil diperbarui.');
    }

    public function destroy(Tugas $tugas)
    {
        if (Auth::user()->guru->nik != $tugas->guru_nik) {
            abort(403, 'Anda tidak memiliki akses ke tugas ini');
        }

        $tugas->delete();

        return redirect()->route('guru.tugas.index')
            ->with('success', 'Tugas berhasil dihapus.');
    }

    /**
     * Menambahkan komentar sebagai guru
     */
    public function storeKomentar(Request $request, Tugas $tugas)
    {
        if (Auth::user()->guru->nik != $tugas->guru_nik) {
            abort(403, 'Anda tidak memiliki akses ke tugas ini');
        }

        $request->validate([
            'isi_komentar' => 'required|string|max:1000',
        ]);

        Komentar::create([
            'tugas_id' => $tugas->id,
            'user_id' => Auth::id(),
            'user_type' => 'guru',
            'isi_komentar' => $request->isi_komentar,
        ]);

        return redirect()->back()
            ->with('success', 'Komentar berhasil ditambahkan');
    }

    /**
     * Menghapus komentar
     */
    public function destroyKomentar(Komentar $komentar)
    {
        $tugas = Tugas::find($komentar->tugas_id);

        if (!$tugas || Auth::user()->guru->nik != $tugas->guru_nik) {
            abort(403, 'Anda tidak memiliki akses ke komentar ini');
        }

        if ($tugas->guru_nik == Auth::user()->guru->nik) {
            $komentar->delete();
        } elseif ($komentar->user_type === 'guru' && $komentar->user_id == Auth::id()) {
            $komentar->delete();
        } else {
            abort(403, 'Anda tidak memiliki izin untuk menghapus komentar ini.');
        }

        return redirect()->back()
            ->with('success', 'Komentar berhasil dihapus');
    }

    public function pengumpulan(Tugas $tugas)
    {
        $guru = Auth::user()->guru;

        if (!$guru) {
            abort(403, 'Anda tidak memiliki akses guru');
        }

        // Pastikan tugas milik guru ini
        if ($tugas->guru_nik != $guru->nik) {
            abort(403, 'Anda tidak memiliki akses ke tugas ini');
        }

        // Ambil semua pengumpulan tugas untuk tugas ini
        $pengumpulan = PengumpulanTugas::with(['siswa'])
            ->where('tugas_id', $tugas->id)
            ->orderBy('submitted_at', 'desc')
            ->paginate(20);

        // Hitung statistik
        $totalSiswa = Siswa::where('kelas_id', $tugas->kelas_id)->count();
        $sudahKumpul = PengumpulanTugas::where('tugas_id', $tugas->id)->count();
        $belumKumpul = $totalSiswa - $sudahKumpul;
        $terlambat = PengumpulanTugas::where('tugas_id', $tugas->id)
            ->where('status', 'late')
            ->count();

        return view('guru.tugas.pengumpulan', compact(
            'tugas',
            'pengumpulan',
            'totalSiswa',
            'sudahKumpul',
            'belumKumpul',
            'terlambat'
        ));
    }

    public function beriNilai(Request $request, Tugas $tugas)
    {
        $guru = Auth::user()->guru;

        if (!$guru) {
            abort(403, 'Anda tidak memiliki akses guru');
        }

        if ($tugas->guru_nik != $guru->nik) {
            abort(403, 'Anda tidak memiliki akses ke tugas ini');
        }

        $request->validate([
            'pengumpulan_id' => 'required|exists:pengumpulan_tugas,id',
            'nilai' => 'required|numeric|min:0|max:100',
            'feedback' => 'nullable|string|max:1000',
        ]);

        $pengumpulan = PengumpulanTugas::find($request->pengumpulan_id);

        // Pastikan pengumpulan ini untuk tugas yang benar
        if ($pengumpulan->tugas_id != $tugas->id) {
            abort(403, 'Pengumpulan tidak sesuai');
        }

        $pengumpulan->update([
            'nilai' => $request->nilai,
            'feedback' => $request->feedback,
            'status' => 'graded',
        ]);

        return redirect()->route('guru.tugas.pengumpulan', $tugas)
            ->with('success', 'Nilai berhasil diberikan.');
    }

    public function hapusPengumpulan(PengumpulanTugas $pengumpulan)
    {
        $guru = Auth::user()->guru;

        if (!$guru) {
            abort(403, 'Anda tidak memiliki akses guru');
        }

        // Pastikan pengumpulan untuk tugas milik guru ini
        $tugas = $pengumpulan->tugas;
        if ($tugas->guru_nik != $guru->nik) {
            abort(403, 'Anda tidak memiliki akses');
        }

        $pengumpulan->delete();

        return redirect()->back()
            ->with('success', 'Pengumpulan berhasil dihapus.');
    }
}