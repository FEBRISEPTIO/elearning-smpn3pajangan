<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tugas;
use App\Models\Komentar;
use App\Models\PengumpulanTugas;
use App\Models\MataPelajaran;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TugasSiswaController extends Controller
{
    /**
     * Helper method untuk mendapatkan data siswa
     */
    private function getSiswa()
    {
        $user = Auth::user();
        return Siswa::where('nisn', $user->username)->first();
    }

    /**
     * Helper method untuk mendapatkan ID/NISN siswa
     * Karena tabel siswa tidak punya 'id', kita gunakan 'nisn'
     */
    private function getSiswaIdentifier()
    {
        $siswa = $this->getSiswa();
        return $siswa ? $siswa->nisn : null; // Gunakan NISN sebagai identifier
    }

    public function index(Request $request)
    {
        $siswa = $this->getSiswa();

        if (!$siswa) {
            return view('siswa.tugas.index', [
                'tugas' => collect([])->paginate(10),
                'mapels' => MataPelajaran::orderBy('nama_mapel', 'asc')->get(),
                'error' => 'Data siswa tidak ditemukan. NISN: ' . Auth::user()->username
            ]);
        }

        // Check jika siswa belum punya kelas
        if (!$siswa->kelas_id) {
            return view('siswa.tugas.index', [
                'tugas' => collect([])->paginate(10),
                'mapels' => MataPelajaran::orderBy('nama_mapel', 'asc')->get(),
                'error' => 'Anda belum memiliki kelas yang ditetapkan. Silakan hubungi administrator.'
            ]);
        }

        $query = Tugas::where('kelas_id', $siswa->kelas_id)
            ->with(['kelas', 'mataPelajaran', 'guru'])
            ->withCount('komentar');

        // Filter
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
        $mapels = MataPelajaran::orderBy('nama_mapel', 'asc')->get();

        return view('siswa.tugas.index', compact('tugas', 'mapels'));
    }

    public function show(Tugas $tugas)
    {
        $siswa = $this->getSiswa();

        if (!$siswa) {
            return redirect()->route('siswa.tugas.index')
                ->with('error', 'Data siswa tidak ditemukan.');
        }

        // Cek apakah siswa punya kelas
        if (!$siswa->kelas_id) {
            return redirect()->route('siswa.tugas.index')
                ->with('error', 'Anda belum memiliki kelas yang ditetapkan.');
        }

        // Cek apakah tugas untuk kelas siswa
        if ($tugas->kelas_id != $siswa->kelas_id) {
            abort(403, 'Anda tidak memiliki akses ke tugas ini');
        }

        // Load dengan count komentar
        $tugas->loadCount('komentar');

        // Cek pengumpulan siswa - GUNAKAN NISN
        $pengumpulan = PengumpulanTugas::where('tugas_id', $tugas->id)
            ->where('nisn', $siswa->nisn) // GUNAKAN NISN BUKAN ID
            ->first();

        // Komentar untuk tugas ini
        $komentars = Komentar::where('tugas_id', $tugas->id)
            ->whereNull('materi_id')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('siswa.tugas.show', compact('tugas', 'pengumpulan', 'komentars'));
    }

    public function storeKomentar(Request $request, Tugas $tugas)
    {
        $siswa = $this->getSiswa();

        if (!$siswa) {
            return redirect()->back()
                ->with('error', 'Data siswa tidak ditemukan.');
        }

        if (!$siswa->kelas_id) {
            return redirect()->back()
                ->with('error', 'Anda belum memiliki kelas yang ditetapkan.');
        }

        if ($tugas->kelas_id != $siswa->kelas_id) {
            abort(403, 'Anda tidak dapat mengomentari tugas di kelas lain');
        }

        $request->validate([
            'isi_komentar' => 'required|string|max:1000',
        ]);

        Komentar::create([
            'tugas_id' => $tugas->id,
            'user_id' => Auth::id(),
            'user_type' => 'siswa',
            'isi_komentar' => $request->isi_komentar,
        ]);

        return redirect()->back()
            ->with('success', 'Komentar berhasil ditambahkan');
    }

    public function destroyKomentar(Komentar $komentar)
    {
        $siswa = $this->getSiswa();

        if (!$siswa) {
            abort(403, 'Data siswa tidak ditemukan');
        }

        // Pastikan hanya pemilik komentar yang bisa menghapus
        if ($komentar->user_type === 'siswa' && $komentar->user_id == Auth::id()) {
            // Pastikan komentar ini untuk tugas di kelas siswa
            if ($komentar->tugas && $komentar->tugas->kelas_id == $siswa->kelas_id) {
                $komentar->delete();
            } else {
                abort(403, 'Anda tidak memiliki akses ke komentar ini');
            }
        } else {
            abort(403, 'Anda tidak memiliki izin untuk menghapus komentar ini.');
        }

        return redirect()->back()
            ->with('success', 'Komentar berhasil dihapus');
    }

    public function submitTugas(Request $request, Tugas $tugas)
    {
        $siswa = $this->getSiswa();

        if (!$siswa) {
            return redirect()->back()
                ->with('error', 'Data siswa tidak ditemukan. NISN: ' . Auth::user()->username);
        }

        if (!$siswa->kelas_id) {
            return redirect()->back()
                ->with('error', 'Anda belum memiliki kelas yang ditetapkan.');
        }

        if ($tugas->kelas_id != $siswa->kelas_id) {
            abort(403, 'Anda tidak dapat mengumpulkan tugas ini');
        }

        if (now()->greaterThan($tugas->deadline)) {
            return redirect()->back()
                ->with('error', 'Maaf, waktu pengumpulan telah berakhir.');
        }

        $request->validate([
            'file_tugas' => 'required|file|max:10240',
            'keterangan' => 'nullable|string|max:500',
        ]);

        try {
            // Upload file
            $fileName = time() . '_' . $request->file('file_tugas')->getClientOriginalName();
            $filePath = $request->file('file_tugas')->storeAs('pengumpulan_tugas', $fileName, 'public');

            // Tentukan status
            $status = now()->greaterThan($tugas->deadline) ? 'late' : 'submitted';

            // **DEBUG: Tampilkan data sebelum save**
            // dd([
            //     'Siswa NISN' => $siswa->nisn,
            //     'Siswa Data' => $siswa->toArray(),
            //     'Data untuk insert' => [
            //         'tugas_id' => $tugas->id,
            //         'siswa_id' => $siswa->nisn,
            //         'file' => $filePath,
            //         'keterangan' => $request->keterangan,
            //         'status' => $status
            //     ]
            // ]);

            // Simpan pengumpulan - GUNAKAN NISN
            $pengumpulan = PengumpulanTugas::updateOrCreate(
                [
                    'tugas_id' => $tugas->id,
                    'nisn' => $siswa->nisn, // GUNAKAN NISN
                ],
                [
                    'file' => $filePath,
                    'keterangan' => $request->keterangan,
                    'status' => $status,
                    'submitted_at' => now(),
                ]
            );

            return redirect()->back()
                ->with('success', 'Tugas berhasil dikumpulkan.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengumpulkan tugas: ' . $e->getMessage());
        }
    }

    /**
     * Download file tugas yang sudah dikumpulkan
     */
    public function downloadTugas(PengumpulanTugas $pengumpulan)
    {
        $siswa = $this->getSiswa();

        // Perbaiki pengecekan: bandingkan dengan NISN
        if (!$siswa || $pengumpulan->nisn != $siswa->nisn) {
            abort(403, 'Anda tidak memiliki akses ke file ini');
        }

        if (!$pengumpulan->file || !Storage::disk('public')->exists($pengumpulan->file)) {
            return redirect()->back()
                ->with('error', 'File tidak ditemukan.');
        }

        return Storage::disk('public')->download($pengumpulan->file);
    }
}