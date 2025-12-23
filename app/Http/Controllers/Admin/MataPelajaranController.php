<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;

class MataPelajaranController extends Controller
{
    // Menampilkan daftar mata pelajaran
    public function index()
    {
        $mapel = MataPelajaran::orderBy('nama_mapel')->get();
        return view('admin.mapel.index', compact('mapel'));
    }

    // Menampilkan form tambah mata pelajaran
    public function create()
    {
        return view('admin.mapel.create');
    }

    // Menyimpan data mata pelajaran baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_mapel' => 'required|string|max:100|unique:mata_pelajaran,nama_mapel',
        ], [
            'nama_mapel.required' => 'Nama mata pelajaran wajib diisi',
            'nama_mapel.unique' => 'Mata pelajaran sudah ada',
        ]);

        MataPelajaran::create([
            'nama_mapel' => $request->nama_mapel,
        ]);

        return redirect()->route('admin.mapel.index')
            ->with('success', 'Data mata pelajaran berhasil ditambahkan!');
    }

    // Menampilkan form edit mata pelajaran
    public function edit($id)
    {
        $mapel = MataPelajaran::findOrFail($id);
        return view('admin.mapel.edit', compact('mapel'));
    }

    // Update data mata pelajaran
    public function update(Request $request, $id)
    {
        $mapel = MataPelajaran::findOrFail($id);

        $request->validate([
            'nama_mapel' => 'required|string|max:100|unique:mata_pelajaran,nama_mapel,' . $mapel->id,
        ], [
            'nama_mapel.required' => 'Nama mata pelajaran wajib diisi',
            'nama_mapel.unique' => 'Mata pelajaran sudah ada',
        ]);

        $mapel->update([
            'nama_mapel' => $request->nama_mapel,
        ]);

        return redirect()->route('admin.mapel.index')
            ->with('success', 'Data mata pelajaran berhasil diupdate!');
    }

    // Hapus data mata pelajaran
    public function destroy($id)
    {
        $mapel = MataPelajaran::findOrFail($id);

        // Cek apakah mata pelajaran memiliki relasi
        if ($mapel->mapelKelas()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Mata pelajaran tidak dapat dihapus karena masih digunakan!');
        }

        $mapel->delete();

        return redirect()->route('admin.mapel.index')
            ->with('success', 'Data mata pelajaran berhasil dihapus!');
    }
}