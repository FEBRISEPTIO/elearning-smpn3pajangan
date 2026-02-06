<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tugas;
use App\Models\PengumpulanTugas;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PengumpulanTugasController extends Controller
{
    /**
     * Menampilkan daftar pengumpulan tugas oleh siswa
     */
    public function index(Tugas $tugas)
    {
        $guru = Auth::user()->guru;

        if (!$guru) {
            abort(403, 'Anda tidak memiliki akses guru');
        }

        // Pastikan tugas milik guru ini
        if ($tugas->guru_nip != $guru->nip) {
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

        return view('guru.pengumpulan-tugas.index', compact(
            'tugas',
            'pengumpulan',
            'totalSiswa',
            'sudahKumpul',
            'belumKumpul',
            'terlambat'
        ));
    }

    /**
     * Memberi nilai pada pengumpulan tugas
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

        return redirect()->route('guru.tugas.pengumpulan', $id)
            ->with('success', 'Nilai berhasil diberikan.');
    }

    /**
     * Menghapus pengumpulan tugas
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

    /**
     * Download file pengumpulan
     */
    public function download(PengumpulanTugas $pengumpulan)
    {
        $guru = Auth::user()->guru;

        if (!$guru) {
            abort(403, 'Anda tidak memiliki akses guru');
        }

        // Pastikan pengumpulan untuk tugas milik guru ini
        $tugas = $pengumpulan->tugas;
        if ($tugas->guru_nip != $guru->nip) {
            abort(403, 'Anda tidak memiliki akses');
        }

        if (!$pengumpulan->file || !Storage::exists('public/' . $pengumpulan->file)) {
            abort(404, 'File tidak ditemukan');
        }

        $filePath = storage_path('app/public/' . $pengumpulan->file);
        $fileName = $pengumpulan->getFileNameAttribute() ?? 'pengumpulan_tugas';

        return response()->download($filePath, $fileName);
    }

    /**
     * Update pengumpulan tugas
     */
    public function update(Request $request, PengumpulanTugas $pengumpulan)
    {
        $guru = Auth::user()->guru;

        if (!$guru) {
            abort(403, 'Anda tidak memiliki akses guru');
        }

        // Pastikan pengumpulan untuk tugas milik guru ini
        $tugas = $pengumpulan->tugas;
        if ($tugas->guru_nip != $guru->nip) {
            abort(403, 'Anda tidak memiliki akses');
        }

        $request->validate([
            'nilai' => 'required|numeric|min:0|max:100',
            'feedback' => 'nullable|string|max:1000',
            'status' => 'required|in:submitted,late,graded',
        ]);

        $pengumpulan->update([
            'nilai' => $request->nilai,
            'feedback' => $request->feedback,
            'status' => $request->status,
        ]);

        return redirect()->route('guru.pengumpulan-tugas.index', $tugas)
            ->with('success', 'Pengumpulan berhasil diperbarui.');
    }

    /**
     * Menampilkan detail pengumpulan tugas
     */
    public function show(PengumpulanTugas $pengumpulan)
    {
        $guru = Auth::user()->guru;

        if (!$guru) {
            abort(403, 'Anda tidak memiliki akses guru');
        }

        // Pastikan pengumpulan untuk tugas milik guru ini
        $tugas = $pengumpulan->tugas;
        if ($tugas->guru_nip != $guru->nip) {
            abort(403, 'Anda tidak memiliki akses');
        }

        return view('guru.pengumpulan-tugas.show', compact('pengumpulan'));
    }

    /**
     * Kirim pengingat ke siswa yang belum kumpul
     */
    public function kirimPengingat(Tugas $tugas)
    {
        $guru = Auth::user()->guru;

        if (!$guru) {
            abort(403, 'Anda tidak memiliki akses guru');
        }

        if ($tugas->guru_nip != $guru->nip) {
            abort(403, 'Anda tidak memiliki akses ke tugas ini');
        }

        // Ambil siswa yang belum mengumpulkan
        $siswaBelumKumpul = Siswa::where('kelas_id', $tugas->kelas_id)
            ->whereNotIn('nisn', function ($query) use ($tugas) {
                $query->select('nisn')
                    ->from('pengumpulan_tugas')
                    ->where('tugas_id', $tugas->id);
            })
            ->get();

        $jumlah = $siswaBelumKumpul->count();

        // Di sini Anda bisa menambahkan logika untuk mengirim notifikasi
        // Misalnya melalui email, WhatsApp, atau sistem notifikasi internal

        return redirect()->back()
            ->with('success', "Pengingat telah dikirim ke {$jumlah} siswa yang belum mengumpulkan.");
    }

    /**
     * Export nilai ke CSV
     */
    public function exportNilai(Tugas $tugas)
    {
        $guru = Auth::user()->guru;

        if (!$guru) {
            abort(403, 'Anda tidak memiliki akses guru');
        }

        if ($tugas->guru_nip != $guru->nip) {
            abort(403, 'Anda tidak memiliki akses ke tugas ini');
        }

        // Ambil semua siswa di kelas
        $siswaKelas = Siswa::where('kelas_id', $tugas->kelas_id)->get();

        // Ambil semua pengumpulan
        $pengumpulan = PengumpulanTugas::with('siswa')
            ->where('tugas_id', $tugas->id)
            ->get()
            ->keyBy('nisn');

        $data = [];
        foreach ($siswaKelas as $siswa) {
            $item = $pengumpulan->get($siswa->nisn);

            $data[] = [
                'NISN' => $siswa->nisn,
                'NIS' => $siswa->nis,
                'Nama Siswa' => $siswa->nama,
                'Status' => $item ? ($item->status == 'graded' ? 'Sudah Dinilai' :
                    ($item->status == 'late' ? 'Terlambat' : 'Terkirim')) : 'Belum Kumpul',
                'Waktu Pengumpulan' => $item ? $item->submitted_at->format('d/m/Y H:i') : '-',
                'Nilai' => $item ? $item->nilai : '-',
                'Feedback' => $item ? $item->feedback : '-',
            ];
        }

        return response()->json([
            'tugas' => $tugas->judul_tugas,
            'kelas' => $tugas->kelas->kelas ?? '-',
            'mapel' => $tugas->mataPelajaran->nama_mapel ?? '-',
            'deadline' => $tugas->deadline->format('d/m/Y H:i'),
            'data' => $data,
        ]);
    }
}
