<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    // Menampilkan daftar kelas
    public function index()
    {
        $kelas = Kelas::orderBy('kelas')->get();
        return view('admin.kelas.index', compact('kelas'));
    }

    // Menampilkan form tambah kelas
    public function create()
    {
        return view('admin.kelas.create');
    }

    // Menyimpan data kelas baru
    public function store(Request $request)
    {
        $request->validate([
            'kelas' => 'required|string|max:50|unique:kelas,kelas',
        ], [
            'kelas.required' => 'Nama kelas wajib diisi',
            'kelas.unique' => 'Kelas sudah ada',
        ]);

        Kelas::create([
            'kelas' => $request->kelas,
        ]);

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Data kelas berhasil ditambahkan!');
    }

    // Menampilkan form edit kelas
    public function edit($id)
    {
        $kelas = Kelas::findOrFail($id);
        return view('admin.kelas.edit', compact('kelas'));
    }

    // Update data kelas
    public function update(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);

        $request->validate([
            'kelas' => 'required|string|max:50|unique:kelas,kelas,' . $kelas->id,
        ], [
            'kelas.required' => 'Nama kelas wajib diisi',
            'kelas.unique' => 'Kelas sudah ada',
        ]);

        $kelas->update([
            'kelas' => $request->kelas,
        ]);

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Data kelas berhasil diupdate!');
    }

    // Hapus data kelas
    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);

        // Cek apakah kelas memiliki siswa
        if ($kelas->siswa()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Kelas tidak dapat dihapus karena masih memiliki siswa!');
        }

        $kelas->delete();

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Data kelas berhasil dihapus!');
    }
}