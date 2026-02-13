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
use Illuminate\Support\Facades\Storage;

class TugasController extends Controller
{
    /**
     * Menampilkan daftar tugas
     */
    public function index(Request $request)
    {
        // Ambil user yang login
        $user = Auth::user();

        // Cek apakah user memiliki data guru
        if (!$user->guru) {
            return redirect()->route('home')
                ->with('error', 'Data guru tidak ditemukan untuk akun Anda');
        }

        $guruNik = $user->guru->nip;

        // Mulai query
        $query = Tugas::where('guru_nip', $guruNik)
            ->with(['kelas', 'mataPelajaran'])
            ->withCount('komentar');

        // Filter berdasarkan request
        if ($request->filled('kelas')) {
            $query->where('kelas_id', $request->kelas);
        }

        if ($request->filled('mapel')) {
            $query->where('mapel_id', $request->mapel);
        }

        if ($request->filled('status')) {
            if ($request->status == 'aktif') {
                $query->where('deadline', '>', now());
            } elseif ($request->status == 'selesai') {
                $query->where('deadline', '<=', now());
            }
        }

        if ($request->filled('search')) {
            $query->where('judul_tugas', 'like', '%' . $request->search . '%');
        }

        // Urutkan dan paginate
        $tugas = $query->orderBy('deadline', 'asc')->paginate(10);

        // Data untuk filter dropdown
        $kelas = Kelas::orderBy('kelas')->get();
        $mapels = MataPelajaran::orderBy('nama_mapel')->get();

        return view('guru.tugas.index', compact('tugas', 'kelas', 'mapels'));
    }

    /**
     * Menampilkan form create
     */
    public function create()
    {
        $user = Auth::user();

        // Cek akses guru
        if (!$user->guru) {
            return back()->with('error', 'Data guru tidak ditemukan');
        }

        $kelas = Kelas::orderBy('kelas')->get();
        $mapels = MataPelajaran::orderBy('nama_mapel')->get();

        return view('guru.tugas.create', compact('kelas', 'mapels'));
    }

    /**
     * Menyimpan tugas baru
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Cek akses guru
        if (!$user->guru) {
            return back()->with('error', 'Data guru tidak ditemukan');
        }

        // Validasi input
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kelas_id' => 'required|exists:kelas,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'deadline' => 'required|date|after:now',
            'konten_tugas' => 'nullable|string',
            'file_tugas' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,jpg,jpeg,png|max:5120', // Max 5MB
        ]);

        $filePath = null;
        if ($request->hasFile('file_tugas')) {
            $filePath = $request->file('file_tugas')->store('tugas', 'public');
        }

        // Simpan ke database
        Tugas::create([
            'judul_tugas' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tugas' => $request->konten_tugas ?? 'Tidak ada konten tambahan',
            'deadline' => $request->deadline,
            'mapel_id' => $request->mata_pelajaran_id,
            'kelas_id' => $request->kelas_id,
            'guru_nip' => $user->guru->nip,
            'file' => $filePath,
        ]);

        return redirect()->route('guru.tugas.index')
            ->with('success', 'Tugas berhasil dibuat');
    }

    /**
     * Menampilkan detail tugas
     */
    public function show($id)
    {
        $user = Auth::user();

        // Cek akses guru
        if (!$user->guru) {
            abort(403, 'Data guru tidak ditemukan untuk akun Anda');
        }

        // Cari tugas
        $tugas = Tugas::with(['kelas', 'mataPelajaran'])
            ->findOrFail($id);

        // Authorization: cek apakah tugas milik guru ini
        if ($tugas->guru_nip != $user->guru->nip) {
            abort(403, 'ANDA TIDAK MEMILIKI AKSES KE TUGAS INI');
        }

        // Ambil komentar
        $komentars = Komentar::where('tugas_id', $id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('guru.tugas.show', compact('tugas', 'komentars'));
    }

    /**
     * Menampilkan form edit
     */
    public function edit($id)
    {
        $user = Auth::user();

        // Cek akses guru
        if (!$user->guru) {
            abort(403, 'Data guru tidak ditemukan');
        }

        // Cari tugas
        $tugas = Tugas::findOrFail($id);

        // Authorization
        if ($tugas->guru_nip != $user->guru->nip) {
            abort(403, 'ANDA TIDAK MEMILIKI AKSES KE TUGAS INI');
        }

        $kelas = Kelas::orderBy('kelas')->get();
        $mapels = MataPelajaran::orderBy('nama_mapel')->get();

        return view('guru.tugas.create', compact('tugas', 'kelas', 'mapels'));
    }

    /**
     * Update tugas
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();

        // Cek akses guru
        if (!$user->guru) {
            abort(403, 'Data guru tidak ditemukan');
        }

        // Cari tugas
        $tugas = Tugas::findOrFail($id);

        // Authorization
        if ($tugas->guru_nip != $user->guru->nip) {
            abort(403, 'ANDA TIDAK MEMILIKI AKSES KE TUGAS INI');
        }

        // Validasi
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kelas_id' => 'required|exists:kelas,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'deadline' => 'required|date',
            'konten_tugas' => 'nullable|string',
            'file_tugas' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,jpg,jpeg,png|max:5120',
        ]);

        $data = [
            'judul_tugas' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tugas' => $request->konten_tugas ?? 'Tidak ada konten tambahan',
            'deadline' => $request->deadline,
            'mapel_id' => $request->mata_pelajaran_id,
            'kelas_id' => $request->kelas_id,
        ];

        if ($request->hasFile('file_tugas')) {
            // Hapus file lama jika ada
            if ($tugas->file && Storage::disk('public')->exists($tugas->file)) {
                Storage::disk('public')->delete($tugas->file);
            }
            $data['file'] = $request->file('file_tugas')->store('tugas', 'public');
        }

        // Update tugas
        $tugas->update($data);

        return redirect()->route('guru.tugas.show', $tugas->id)
            ->with('success', 'Tugas berhasil diperbarui');
    }

    /**
     * Hapus tugas
     */
    public function destroy($id)
    {
        $user = Auth::user();

        // Cek akses guru
        if (!$user->guru) {
            abort(403, 'Data guru tidak ditemukan');
        }

        // Cari tugas
        $tugas = Tugas::findOrFail($id);

        // Authorization
        if ($tugas->guru_nip != $user->guru->nip) {
            abort(403, 'ANDA TIDAK MEMILIKI AKSES KE TUGAS INI');
        }

        // Hapus tugas
        $tugas->delete();

        return redirect()->route('guru.tugas.index')
            ->with('success', 'Tugas berhasil dihapus');
    }

    /**
     * Halaman pengumpulan tugas
     */
    public function pengumpulan($id)
    {
        $user = Auth::user();

        if (!$user->guru) {
            abort(403, 'Data guru tidak ditemukan');
        }

        // Cari tugas
        $tugas = Tugas::with('kelas')->findOrFail($id);

        // Authorization
        if ($tugas->guru_nip != $user->guru->nip) {
            abort(403, 'ANDA TIDAK MEMILIKI AKSES KE TUGAS INI');
        }

        // Ambil pengumpulan
        $pengumpulan = PengumpulanTugas::with(['siswa'])
            ->where('tugas_id', $id)
            ->orderBy('submitted_at', 'desc')
            ->paginate(20);

        // Hitung statistik - SESUAIKAN DENGAN ENUM
        $totalSiswa = Siswa::where('kelas_id', $tugas->kelas_id)->count();
        $sudahKumpul = PengumpulanTugas::where('tugas_id', $id)->count();
        $belumKumpul = $totalSiswa - $sudahKumpul;
        $terlambat = PengumpulanTugas::where('tugas_id', $id)
            ->where('status', 'late') // Sesuai ENUM
            ->count();

        // Ambil siswa yang belum mengumpulkan
        $siswaBelumKumpul = Siswa::where('kelas_id', $tugas->kelas_id)
            ->whereNotIn('nisn', function ($query) use ($id) {
                $query->select('nisn')
                    ->from('pengumpulan_tugas')
                    ->where('tugas_id', $id);
            })
            ->get();

        return view('guru.tugas.pengumpulan', compact(
            'tugas',
            'pengumpulan',
            'totalSiswa',
            'sudahKumpul',
            'belumKumpul',
            'terlambat',
            'siswaBelumKumpul'
        ));
    }

    /**
     * Beri nilai tugas
     */
    public function beriNilai(Request $request, $id)
    {
        $user = Auth::user();

        if (!$user->guru) {
            abort(403, 'Data guru tidak ditemukan');
        }

        // Cari tugas
        $tugas = Tugas::findOrFail($id);

        // Authorization
        if ($tugas->guru_nip != $user->guru->nip) {
            abort(403, 'Anda tidak memiliki akses ke tugas ini');
        }

        $request->validate([
            'pengumpulan_id' => 'required|exists:pengumpulan_tugas,id',
            'nilai' => 'required|numeric|min:0|max:100',
            'feedback' => 'nullable|string|max:1000',
        ]);

        $pengumpulan = PengumpulanTugas::findOrFail($request->pengumpulan_id);

        // Pastikan pengumpulan untuk tugas yang benar
        if ($pengumpulan->tugas_id != $id) {
            abort(403, 'Pengumpulan tidak sesuai');
        }

        // Gunakan 'graded' sesuai ENUM database
        $pengumpulan->update([
            'nilai' => $request->nilai,
            'feedback' => $request->feedback,
            'status' => 'graded', // Sesuai ENUM('submitted', 'late', 'graded')
        ]);

        // Sinkronisasi dengan model Nilai
        \App\Models\Nilai::updateOrCreate(
            [
                'nisn' => $pengumpulan->nisn,
                'guru_nip' => $tugas->guru_nip,
                'mapel_id' => $tugas->mapel_id,
                'tugas_id' => $tugas->id,
            ],
            [
                'nilai' => $request->nilai,
                'keterangan' => $request->feedback,
                'tgl_penilaian' => now(),
            ]
        );

        return redirect()->route('guru.tugas.pengumpulan', $id)
            ->with('success', 'Nilai berhasil diberikan.');
    }

    /**
     * Tambah komentar
     */
    public function storeKomentar(Request $request, $id)
    {
        $user = Auth::user();

        if (!$user->guru) {
            abort(403, 'Data guru tidak ditemukan');
        }

        // Cari tugas
        $tugas = Tugas::findOrFail($id);

        // Authorization
        if ($tugas->guru_nip != $user->guru->nip) {
            abort(403, 'ANDA TIDAK MEMILIKI AKSES KE TUGAS INI');
        }

        // Validasi
        $request->validate([
            'isi_komentar' => 'required|string|max:1000',
        ]);

        // Buat komentar
        Komentar::create([
            'tugas_id' => $id,
            'user_id' => $user->id,
            'user_type' => 'guru',
            'isi_komentar' => $request->isi_komentar,
        ]);

        return redirect()->back()
            ->with('success', 'Komentar berhasil ditambahkan');
    }

    /**
     * Hapus komentar
     */
    public function destroyKomentar($komentarId)
    {
        $user = Auth::user();

        if (!$user->guru) {
            abort(403, 'Data guru tidak ditemukan');
        }

        // Cari komentar
        $komentar = Komentar::findOrFail($komentarId);

        // Cari tugas terkait
        $tugas = Tugas::find($komentar->tugas_id);

        // Authorization: hanya guru pemilik tugas yang bisa hapus
        if (!$tugas || $tugas->guru_nip != $user->guru->nip) {
            abort(403, 'Anda tidak memiliki akses ke komentar ini');
        }

        // Hapus komentar
        $komentar->delete();

        return redirect()->back()
            ->with('success', 'Komentar berhasil dihapus');
    }

    /**
     * Hapus pengumpulan tugas
     */
    public function hapusPengumpulan($pengumpulanId)
    {
        $user = Auth::user();

        if (!$user->guru) {
            abort(403, 'Data guru tidak ditemukan');
        }

        // Cari pengumpulan
        $pengumpulan = PengumpulanTugas::findOrFail($pengumpulanId);

        // Cari tugas terkait
        $tugas = $pengumpulan->tugas;

        // Authorization: hanya guru pemilik tugas
        if ($tugas->guru_nip != $user->guru->nip) {
            abort(403, 'Anda tidak memiliki akses');
        }

        $pengumpulan->delete();

        return redirect()->back()
            ->with('success', 'Pengumpulan berhasil dihapus.');
    }



    // Download file pengumpulan
    public function downloadPengumpulan($id)
    {
        $pengumpulan = PengumpulanTugas::findOrFail($id);

        $tugas = $pengumpulan->tugas;
        if ($tugas->guru_id != Auth::id()) {
            // abort(403, 'Unauthorized access');
        }

        if (!$pengumpulan->file || !Storage::disk('public')->exists($pengumpulan->file)) {
            return redirect()->back()->with('error', 'File tidak ditemukan.');
        }

        return Storage::disk('public')->download($pengumpulan->file);
    }

    /**
     * Download file lampiran tugas
     */
    public function downloadFile(Tugas $tugas)
    {
        if ($tugas->guru_id != Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke tugas ini');
        }

        if (!$tugas->file || !Storage::disk('public')->exists($tugas->file)) {
            return redirect()->back()->with('error', 'File tidak ditemukan.');
        }

        return Storage::disk('public')->download($tugas->file);
    }
}
