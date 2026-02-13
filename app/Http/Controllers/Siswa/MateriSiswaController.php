<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use App\Models\Komentar;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MateriSiswaController extends Controller
{
    /**
     * Menampilkan daftar materi untuk siswa
     */
    public function index(Request $request)
    {
        // Ambil kelas siswa
        $siswaKelasId = Auth::user()->siswa->kelas_id ?? null;

        if (!$siswaKelasId) {
            return view('siswa.materi.index', [
                'materi' => collect(),
                'mapels' => MataPelajaran::orderBy('nama_mapel')->get()
            ])->with('error', 'Anda belum terdaftar di kelas manapun.');
        }

        // PERBAIKAN: Tambahkan eager loading untuk komentar count
        $query = Materi::with(['kelas', 'mataPelajaran', 'guru', 'komentar'])
            ->where('kelas_id', $siswaKelasId)
            ->where(function ($q) {
                $q->whereNotNull('materi')
                    ->orWhereNotNull('file');
            });

        // Filter berdasarkan mata pelajaran
        if ($request->has('mapel') && $request->mapel != '') {
            $query->where('mapel_id', $request->mapel);
        }

        // Filter pencarian
        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('judul_materi', 'like', '%' . $request->search . '%')
                    ->orWhere('deskripsi', 'like', '%' . $request->search . '%');
            });
        }

        // Urutkan dari yang terbaru
        $materi = $query->orderBy('created_at', 'desc')->paginate(12);

        // Ambil data untuk filter
        $mapels = MataPelajaran::orderBy('nama_mapel')->get();

        return view('siswa.materi.index', compact('materi', 'mapels'));
    }

    /**
     * Menampilkan detail materi
     */
    public function show($id)
    {
        $materi = Materi::with([
            'kelas',
            'mataPelajaran',
            'guru',
            'komentar' // PERBAIKAN: Load komentar
        ])->findOrFail($id);

        // Pastikan siswa memiliki akses ke materi ini
        $siswaKelasId = Auth::user()->siswa->kelas_id ?? null;
        if ($materi->kelas_id != $siswaKelasId) {
            abort(403, 'Anda tidak memiliki akses ke materi ini.');
        }

        return view('siswa.materi.show', compact('materi'));
    }

    public function storeKomentar(Request $request, Materi $materi)
    {
        $request->validate([
            'isi_komentar' => 'required|string|max:1000',
        ]);

        // Pastikan siswa hanya bisa mengomentari materi di kelasnya
        $siswa = Auth::user()->siswa;

        if (!$siswa) {
            abort(403, 'Anda tidak memiliki akses siswa');
        }

        if ($materi->kelas_id != $siswa->kelas_id) {
            abort(403, 'Anda tidak dapat mengomentari materi di kelas lain');
        }

        // Simpan komentar dengan user_type = 'siswa'
        Komentar::create([
            'materi_id' => $materi->id,
            'user_id' => Auth::id(),
            'user_type' => 'siswa',
            'isi_komentar' => $request->isi_komentar,
        ]);

        return redirect()->back()
            ->with('success', 'Komentar berhasil ditambahkan');
    }

    /**
     * Menghapus komentar siswa
     */
    public function destroyKomentar(Komentar $komentar)
    {
        $siswa = Auth::user()->siswa;

        if (!$siswa) {
            abort(403, 'Anda tidak memiliki akses siswa');
        }

        // Pastikan hanya pemilik komentar yang bisa menghapus
        if ($komentar->user_type === 'siswa' && $komentar->user_id == Auth::id()) {
            $komentar->delete();
        } else {
            abort(403, 'Anda tidak memiliki izin untuk menghapus komentar ini.');
        }

        return redirect()->back()
            ->with('success', 'Komentar berhasil dihapus');
    }

    /**
     * Download file materi
     */
    public function download(Materi $materi)
    {
        // Pastikan siswa memiliki akses ke materi ini
        $siswaKelasId = Auth::user()->siswa->kelas_id ?? null;
        if ($materi->kelas_id != $siswaKelasId) {
            abort(403, 'Anda tidak memiliki akses ke materi ini.');
        }

        if (!$materi->file || !Storage::disk('public')->exists($materi->file)) {
            return redirect()->back()->with('error', 'File tidak ditemukan.');
        }

        return Storage::disk('public')->download($materi->file);
    }
}
