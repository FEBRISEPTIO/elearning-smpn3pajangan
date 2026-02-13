<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Materi;
use App\Models\Komentar;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class MateriController extends Controller
{
    public function index(Request $request)
    {
        $guru = Auth::user()->guru;

        if (!$guru) {
            abort(403, 'Anda tidak memiliki akses guru');
        }

        // Query dasar
        $query = Materi::where('guru_nip', $guru->nip)
            ->with(['kelas', 'mataPelajaran']);

        // Filter berdasarkan kelas jika dipilih
        if ($request->has('kelas') && $request->kelas != '') {
            $query->where('kelas_id', $request->kelas);
        }

        // Filter berdasarkan mata pelajaran jika ada
        if ($request->has('mapel') && $request->mapel != '') {
            $query->where('mapel_id', $request->mapel);
        }

        // Filter berdasarkan judul jika ada
        if ($request->has('search') && $request->search != '') {
            $query->where('judul_materi', 'like', '%' . $request->search . '%');
        }

        $materi = $query->orderBy('created_at', 'desc')->paginate(10);

        // Ambil semua kelas untuk dropdown filter
        $kelas = Kelas::orderBy('kelas', 'asc')->get();

        // Ambil mata pelajaran untuk dropdown filter
        $mapels = MataPelajaran::orderBy('nama_mapel', 'asc')->get();

        return view('guru.materi.index', compact('materi', 'kelas', 'mapels'));
    }

    public function create()
    {
        $guru = Auth::user()->guru;

        if (!$guru) {
            abort(403, 'Anda tidak memiliki akses guru');
        }

        // Ambil semua kelas
        $kelas = Kelas::orderBy('kelas', 'asc')->get();

        // Ambil mata pelajaran
        $mapels = MataPelajaran::orderBy('nama_mapel', 'asc')->get();

        return view('guru.materi.create', compact('kelas', 'mapels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kelas_id' => 'required|exists:kelas,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'file' => 'nullable|file|max:20480', // Hapus mimes untuk testing
            'link_video' => 'nullable|url',
            'konten_teks' => 'nullable|string',
        ]);

        $guru = Auth::user()->guru;

        if (!$guru) {
            abort(403, 'Anda tidak memiliki akses guru');
        }

        $data = [
            'judul_materi' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'mapel_id' => $request->mata_pelajaran_id,
            'kelas_id' => $request->kelas_id,
            'guru_nip' => $guru->nip,
            'materi' => $request->konten_teks,
        ];

        if (empty($data['materi'])) {
            $data['materi'] = 'Tidak ada konten teks';
        }

        if ($request->link_video) {
            $data['materi'] .= "\n\n[Video]: " . $request->link_video;
        }

        // SIMPLE: Simpan file jika ada
        if ($request->hasFile('file')) {
            $fileName = time() . '_' . $request->file('file')->getClientOriginalName();
            $filePath = $request->file('file')->storeAs('materi', $fileName, 'public');
            $data['file'] = $filePath;
        }

        $materi = Materi::create($data);

        return redirect()->route('guru.materi.index')
            ->with('success', 'Materi berhasil ditambahkan.');
    }

    public function show(Materi $materi)
    {
        // Pastikan hanya guru yang membuat materi yang bisa melihat
        if (Auth::user()->guru->nip != $materi->guru_nip) {
            abort(403, 'Anda tidak memiliki akses ke materi ini');
        }

        // Gunakan withCount() untuk menghitung komentar
        $materi = Materi::withCount('komentar')->find($materi->id); // Atau reload dengan count

        // Query komentar untuk ditampilkan
        $komentars = Komentar::where('materi_id', $materi->id)
            ->whereNull('tugas_id')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('guru.materi.show', compact('materi', 'komentars'));
    }
    public function edit(Materi $materi)
    {
        // Pastikan hanya guru yang membuat materi yang bisa edit
        if (Auth::user()->guru->nip != $materi->guru_nip) {
            abort(403, 'Anda tidak memiliki akses ke materi ini');
        }

        $kelas = Kelas::orderBy('kelas', 'asc')->get();
        $mapels = MataPelajaran::orderBy('nama_mapel', 'asc')->get();

        return view('guru.materi.edit', compact('materi', 'kelas', 'mapels'));
    }

    public function update(Request $request, Materi $materi)
    {
        // Pastikan hanya guru yang membuat materi yang bisa update
        if (Auth::user()->guru->nip != $materi->guru_nip) {
            abort(403, 'Anda tidak memiliki akses ke materi ini');
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kelas_id' => 'required|exists:kelas,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'file' => 'nullable|file|max:20480',
            'link_video' => 'nullable|url',
            'konten_teks' => 'nullable|string',
        ]);

        $data = [
            'judul_materi' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'mapel_id' => $request->mata_pelajaran_id,
            'kelas_id' => $request->kelas_id,
            'materi' => $request->konten_teks,
        ];

        // Berikan nilai default jika materi kosong
        if (empty($data['materi'])) {
            $data['materi'] = 'Tidak ada konten teks';
        }

        if ($request->link_video) {
            $data['materi'] .= "\n\n[Video]: " . $request->link_video;
        }

        if ($request->hasFile('file')) {
            // Hapus file lama jika ada
            if ($materi->file && Storage::disk('public')->exists($materi->file)) {
                Storage::disk('public')->delete($materi->file);
            }

            $data['file'] = $request->file('file')->store('materi', 'public');
        } elseif ($request->has('hapus_file')) {
            if ($materi->file && Storage::disk('public')->exists($materi->file)) {
                Storage::disk('public')->delete($materi->file);
            }
            $data['file'] = null;
        }

        $materi->update($data);

        return redirect()->route('guru.materi.show', $materi)
            ->with('success', 'Materi berhasil diperbarui.');
    }

    public function destroy(Materi $materi)
    {
        // Pastikan hanya guru yang membuat materi yang bisa hapus
        if (Auth::user()->guru->nip != $materi->guru_nip) {
            abort(403, 'Anda tidak memiliki akses ke materi ini');
        }

        // Hapus file jika ada
        if ($materi->file && Storage::disk('public')->exists($materi->file)) {
            Storage::disk('public')->delete($materi->file);
        }

        $materi->delete();

        return redirect()->route('guru.materi.index')
            ->with('success', 'Materi berhasil dihapus.');
    }

    /**
     * Menambahkan komentar sebagai guru
     */
    public function storeKomentar(Request $request, Materi $materi)
    {
        // Pastikan hanya guru yang membuat materi yang bisa komentar
        if (Auth::user()->guru->nip != $materi->guru_nip) {
            abort(403, 'Anda tidak memiliki akses ke materi ini');
        }

        $request->validate([
            'isi_komentar' => 'required|string|max:1000',
        ]);

        // Simpan komentar dengan user_type = 'guru'
        Komentar::create([
            'materi_id' => $materi->id,
            'user_id' => Auth::id(),
            'user_type' => 'guru',
            'isi_komentar' => $request->isi_komentar,
        ]);

        return redirect()->back()
            ->with('success', 'Komentar berhasil ditambahkan');
    }

    /**
     * Menghapus komentar (guru bisa hapus semua komentar di materinya)
     */
    public function destroyKomentar(Komentar $komentar)
    {
        $materi = Materi::find($komentar->materi_id);

        // Pastikan komentar ini milik materi yang dibuat oleh guru ini
        if (!$materi || Auth::user()->guru->nip != $materi->guru_nip) {
            abort(403, 'Anda tidak memiliki akses ke komentar ini');
        }

        // Cek apakah guru adalah pembuat materi atau pemilik komentar
        if ($materi->guru_nip == Auth::user()->guru->nip) {
            // Guru adalah pembuat materi, bisa hapus semua komentar
            $komentar->delete();
        } elseif ($komentar->user_type === 'guru' && $komentar->user_id == Auth::id()) {
            // Guru adalah pemilik komentar
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
        // Pastikan hanya guru yang membuat materi yang bisa download
        if (Auth::user()->guru->nip != $materi->guru_nip) {
            abort(403, 'Anda tidak memiliki akses ke materi ini');
        }

        if (!$materi->file || !Storage::disk('public')->exists($materi->file)) {
            return redirect()->back()->with('error', 'File tidak ditemukan.');
        }

        return Storage::disk('public')->download($materi->file);
    }
}
