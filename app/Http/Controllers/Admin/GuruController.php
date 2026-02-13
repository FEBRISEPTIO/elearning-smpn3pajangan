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
            'nip' => 'nullable|string|max:20|unique:guru,nip',
            'nuptk' => 'nullable|string|max:20|unique:guru,nuptk',
            'nama' => 'required|string|max:100',
            'email' => 'nullable|email|max:100|unique:guru,email',
            'jnsklmn' => 'required|in:L,P',
            'jenis_ptk' => 'required|string|max:50',
            'alamat' => 'nullable|string',
            'no_telp' => 'nullable|string|max:15',
        ], [
            'nip.unique' => 'NIP sudah terdaftar.',
            'nuptk.unique' => 'NUPTK sudah terdaftar.',
            'email.unique' => 'Email sudah terdaftar.',
        ]);

        if (!$request->nip && !$request->nuptk) {
            return redirect()->back()->with('error', 'NIP atau NUPTK harus diisi salah satu!')->withInput();
        }

        $loginIdentifier = $request->nip ?: $request->nuptk;

        DB::beginTransaction();

        try {
            // Create guru
            $guru = Guru::create([
                'nip' => $loginIdentifier,
                'nuptk' => $request->nuptk,
                'nama' => $request->nama,
                'email' => $request->email,
                'jnsklmn' => $request->jnsklmn,
                'jenis_ptk' => $request->jenis_ptk,
                'alamat' => $request->alamat,
                'no_telp' => $request->no_telp,
            ]);

            // Create akun untuk guru
            $akun = Akun::create([
                'username' => $loginIdentifier, // Username = NIP or NUPTK
                'password' => Hash::make($loginIdentifier), // Password = NIP or NUPTK
                'user_type' => 'guru',
                'user_id' => $guru->nip,
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
    public function edit($nip)
    {
        $guru = Guru::where('nip', $nip)->firstOrFail();
        $mapel = MataPelajaran::all();
        return view('admin.guru.edit', compact('guru', 'mapel'));
    }

    // Update data guru
    public function update(Request $request, $nip)
    {
        $guru = Guru::where('nip', $nip)->firstOrFail();

        $request->validate([
            'nuptk' => 'nullable|string|max:20|unique:guru,nuptk,' . $guru->nip . ',nip',
            'nama' => 'required|string|max:100',
            'email' => 'nullable|email|max:100|unique:guru,email,' . $guru->nip . ',nip',
            'jnsklmn' => 'required|in:L,P',
            'jenis_ptk' => 'required|string|max:50',
            'alamat' => 'nullable|string',
            'no_telp' => 'nullable|string|max:15',
        ]);

        try {
            $guru->update([
                'nuptk' => $request->nuptk,
                'nama' => $request->nama,
                'email' => $request->email,
                'jnsklmn' => $request->jnsklmn,
                'jenis_ptk' => $request->jenis_ptk,
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
    public function destroy($nip)
    {
        DB::beginTransaction();

        try {
            // Hapus akun terlebih dahulu
            Akun::where('user_id', $nip)
                ->where('user_type', 'guru')
                ->delete();

            // Hapus guru
            Guru::where('nip', $nip)->delete();

            DB::commit();

            return redirect()->route('admin.guru.index')
                ->with('success', 'Data guru berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
