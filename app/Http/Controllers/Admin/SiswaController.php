<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Akun;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class SiswaController extends Controller
{
    // Menampilkan daftar siswa
    public function index()
    {
        $siswa = Siswa::with('kelas')->get();
        $kelas = Kelas::all();
        return view('admin.siswa.index', compact('siswa', 'kelas'));
    }

    // Menampilkan form tambah siswa
    public function create()
    {
        $kelas = Kelas::all();
        return view('admin.siswa.create', compact('kelas'));
    }

    // Menyimpan data siswa baru (VERSI SIMPLIFIED)
    public function store(Request $request)
    {
        // Validasi data
        $request->validate([
            'nisn' => 'required|string|size:10|unique:siswa,nisn',
            'nis' => 'required|string|size:8',
            'nama' => 'required|string|max:100',
            'email' => 'nullable|email|max:100|unique:siswa,email',
            'jnsklmn' => 'required|in:L,P',
            'kelas_id' => 'required|exists:kelas,id',
            'alamat' => 'nullable|string',
            'no_telp' => 'nullable|string|max:15',
        ]);

        DB::beginTransaction();

        try {
            // 1. Create siswa
            $siswa = Siswa::create([
                'nisn' => $request->nisn,
                'nis' => $request->nis,
                'nama' => $request->nama,
                'email' => $request->email,
                'jnsklmn' => $request->jnsklmn,
                'kelas_id' => $request->kelas_id,
                'alamat' => $request->alamat,
                'no_telp' => $request->no_telp,
                'foto' => null, // default null
            ]);

            // 2. Create akun untuk login (username = nisn, password = nis)
            Akun::create([
                'username' => $request->nisn,
                'password' => Hash::make($request->nis),
                'user_type' => 'siswa',
                'user_id' => $siswa->nisn,
                'role_id' => 3, // Role ID untuk siswa
            ]);

            DB::commit();

            return redirect()->route('admin.siswa.index')
                ->with('success', 'Data siswa berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollback();

            // Log error untuk debugging
            \Log::error('Gagal menyimpan siswa: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    // Menampilkan form edit siswa
    public function edit($nisn)
    {
        $siswa = Siswa::where('nisn', $nisn)->firstOrFail();
        $kelas = Kelas::all();
        return view('admin.siswa.edit', compact('siswa', 'kelas'));
    }

    // Update data siswa
    public function update(Request $request, $nisn)
    {
        $siswa = Siswa::where('nisn', $nisn)->firstOrFail();

        $request->validate([
            'nis' => 'required|string|size:8',
            'nama' => 'required|string|max:100',
            'email' => 'nullable|email|max:100|unique:siswa,email,' . $siswa->nisn . ',nisn',
            'jnsklmn' => 'required|in:L,P',
            'kelas_id' => 'required|exists:kelas,id',
            'alamat' => 'nullable|string',
            'no_telp' => 'nullable|string|max:15',
        ]);

        DB::beginTransaction();

        try {
            // Update data siswa
            $siswa->update([
                'nis' => $request->nis,
                'nama' => $request->nama,
                'email' => $request->email,
                'jnsklmn' => $request->jnsklmn,
                'kelas_id' => $request->kelas_id,
                'alamat' => $request->alamat,
                'no_telp' => $request->no_telp,
            ]);

            // Update password di akun jika NIS berubah
            if ($siswa->nis !== $request->nis) {
                $akun = Akun::where('user_id', $siswa->nisn)
                    ->where('user_type', 'siswa')
                    ->first();

                if ($akun) {
                    $akun->update([
                        'password' => Hash::make($request->nis)
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.siswa.index')
                ->with('success', 'Data siswa berhasil diupdate!');

        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    // Hapus data siswa
    public function destroy($nisn)
    {
        DB::beginTransaction();

        try {
            // Hapus akun terlebih dahulu
            Akun::where('user_id', $nisn)
                ->where('user_type', 'siswa')
                ->delete();

            // Hapus siswa
            Siswa::where('nisn', $nisn)->delete();

            DB::commit();

            return redirect()->route('admin.siswa.index')
                ->with('success', 'Data siswa berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}