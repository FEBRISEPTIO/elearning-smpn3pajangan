<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Komentar;
use App\Models\Tugas;
use App\Models\Materi;

class KomentarController extends Controller
{
    public function index()
    {
        // Ambil ID guru yang login
        $guruId = auth()->user()->id; // atau $guruNik = auth()->user()->nik;

        // Ambil tugas dan materi yang dibuat oleh guru ini
        $tugasIds = Tugas::where('guru_id', $guruId)->pluck('id');
        $materiIds = Materi::where('guru_id', $guruId)->pluck('id');

        // Ambil komentar untuk tugas dan materi guru ini
        $komentars = Komentar::with(['user', 'tugas', 'materi'])
            ->where(function ($query) use ($tugasIds, $materiIds) {
                $query->whereIn('tugas_id', $tugasIds)
                    ->orWhereIn('materi_id', $materiIds);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Hitung statistik
        $stats = [
            'total' => $komentars->total(),
            'tugas' => Komentar::whereIn('tugas_id', $tugasIds)->count(),
            'materi' => Komentar::whereIn('materi_id', $materiIds)->count(),
            'pending' => Komentar::where(function ($query) use ($tugasIds, $materiIds) {
                $query->whereIn('tugas_id', $tugasIds)
                    ->orWhereIn('materi_id', $materiIds);
            })->whereNull('replied_at')->count(),
        ];

        return view('guru.komentar.index', compact('komentars', 'stats'));
    }

    public function reply(Request $request, $id)
    {
        $request->validate([
            'balasan' => 'required|string|max:1000',
        ]);

        $komentar = Komentar::findOrFail($id);

        // Cek apakah komentar ini milik tugas/materi guru yang login
        $guruId = auth()->user()->id;

        if ($komentar->tugas && $komentar->tugas->guru_id != $guruId) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke komentar ini.');
        }

        if ($komentar->materi && $komentar->materi->guru_id != $guruId) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke komentar ini.');
        }

        // Update komentar dengan balasan
        $komentar->update([
            'balasan' => $request->balasan,
            'replied_at' => now(),
            'replied_by' => $guruId,
        ]);

        return redirect()->back()->with('success', 'Balasan berhasil dikirim.');
    }

    public function markAsReplied($id)
    {
        $komentar = Komentar::findOrFail($id);

        $komentar->update([
            'replied_at' => now(),
            'replied_by' => auth()->user()->id,
        ]);

        return redirect()->back()->with('success', 'Komentar ditandai sudah dibalas.');
    }
}