<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Akun;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class GuruController extends Controller
{
    // Menampilkan daftar guru
    public function index()
    {
        $guru = Guru::all();
        $mapel = MataPelajaran::all();
        return view('admin.guru.index', compact('guru', 'mapel'));
    }

    // Menampilkan form tambah guru
    public function create()
    {
        $mapel = MataPelajaran::all();
        return view('admin.guru.create', compact('mapel'));
    }

    // Menyimpan data guru baru
    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|string|max:20|unique:guru,nik',
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:guru,email',
            'jnsklmn' => 'required|in:L,P',
            'alamat' => 'nullable|string',
            'no_telp' => 'nullable|string|max:15',
        ]);

        DB::beginTransaction();

        try {
            // Create guru
            $guru = Guru::create([
                'nik' => $request->nik,
                'nama' => $request->nama,
                'email' => $request->email,
                'jnsklmn' => $request->jnsklmn,
                'alamat' => $request->alamat,
                'no_telp' => $request->no_telp,
            ]);

            // Create akun untuk guru
            $akun = Akun::create([
                'username' => $request->nik, // Username = NIK
                'password' => Hash::make($request->nik), // Password = NIK
                'user_type' => 'guru',
                'user_id' => $guru->nik,
                'role_id' => 2, // Role ID untuk guru
            ]);

            DB::commit();

            return redirect()->route('admin.guru.index')
                ->with('success', 'Data guru berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    // Menampilkan form edit guru
    public function edit($nik)
    {
        $guru = Guru::where('nik', $nik)->firstOrFail();
        $mapel = MataPelajaran::all();
        return view('admin.guru.edit', compact('guru', 'mapel'));
    }

    // Update data guru
    public function update(Request $request, $nik)
    {
        $guru = Guru::where('nik', $nik)->firstOrFail();

        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:guru,email,' . $guru->nik . ',nik',
            'jnsklmn' => 'required|in:L,P',
            'alamat' => 'nullable|string',
            'no_telp' => 'nullable|string|max:15',
        ]);

        try {
            $guru->update([
                'nama' => $request->nama,
                'email' => $request->email,
                'jnsklmn' => $request->jnsklmn,
                'alamat' => $request->alamat,
                'no_telp' => $request->no_telp,
            ]);

            return redirect()->route('admin.guru.index')
                ->with('success', 'Data guru berhasil diupdate!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    // Hapus data guru
    public function destroy($nik)
    {
        DB::beginTransaction();

        try {
            // Hapus akun terlebih dahulu
            Akun::where('user_id', $nik)
                ->where('user_type', 'guru')
                ->delete();

            // Hapus guru
            Guru::where('nik', $nik)->delete();

            DB::commit();

            return redirect()->route('admin.guru.index')
                ->with('success', 'Data guru berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Reset password guru
    public function resetPassword($nik)
    {
        DB::beginTransaction();

        try {
            $akun = Akun::where('user_id', $nik)
                ->where('user_type', 'guru')
                ->first();

            if ($akun) {
                $akun->update([
                    'password' => Hash::make($nik) // Reset ke NIK
                ]);
            }

            DB::commit();

            return redirect()->route('admin.guru.index')
                ->with('success', 'Password berhasil direset ke NIK!');

        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


}