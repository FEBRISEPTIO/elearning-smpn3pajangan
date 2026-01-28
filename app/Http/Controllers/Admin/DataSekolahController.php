<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataSekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DataSekolahController extends Controller
{
    // Tampilkan data sekolah
    public function index()
    {
        $data = DataSekolah::first();
        return view('admin.data-sekolah.index', compact('data'));
    }

    // Form edit data sekolah
    public function edit()
    {
        $data = DataSekolah::first();
        if (!$data) {
            $data = new DataSekolah();
        }

        return view('admin.data-sekolah.edit', compact('data'));
    }

    // Update data sekolah
    public function update(Request $request)
    {
        $request->validate([
            'nama_sekolah' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'visi' => 'nullable|string',
            'misi' => 'nullable|string',
            'alamat' => 'required|string',
            'telepon' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'website' => 'nullable|url|max:255',
        ]);

        $data = DataSekolah::first();
        $updateData = $request->all();

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Hapus logo lama jika ada
            if ($data && $data->logo) {
                Storage::delete($data->logo);
            }

            // Simpan logo baru
            $logoPath = $request->file('logo')->store('logo-sekolah', 'public');
            $updateData['logo'] = $logoPath;
        } else {
            // Jika tidak upload logo baru, pertahankan logo lama
            if ($data && $data->logo) {
                $updateData['logo'] = $data->logo;
            }
        }

        if ($data) {
            $data->update($updateData);
        } else {
            DataSekolah::create($updateData);
        }

        return redirect()->route('admin.data-sekolah.index')
            ->with('success', 'Data sekolah berhasil diperbarui!');
    }
}