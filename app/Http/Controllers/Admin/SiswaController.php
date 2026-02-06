<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Akun;
use App\Models\Kelas;
use App\Imports\SiswaImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SiswaController extends Controller
{
    // Menampilkan daftar siswa
    public function index(Request $request)
    {
        $query = Siswa::with('kelas');

        // Pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%")
                    ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        // Filter Kelas
        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }

        // Filter Tingkat
        if ($request->filled('tingkat')) {
            $query->where('tingkat', $request->tingkat);
        }

        // Filter Jenis Kelamin
        if ($request->filled('jenis_kelamin')) {
            $query->where('jnsklmn', $request->jenis_kelamin);
        }

        $siswa = $query->orderBy('nama')->paginate(10)->withQueryString();
        $kelas = Kelas::orderBy('kelas')->get();

        // Get unique tingkat values for filter dropdown
        $tingkatList = Siswa::select('tingkat')->distinct()->whereNotNull('tingkat')->orderBy('tingkat')->pluck('tingkat');

        return view('admin.siswa.index', compact('siswa', 'kelas', 'tingkatList'));
    }

    // Menampilkan form tambah siswa
    public function create()
    {
        $kelas = Kelas::all();
        return view('admin.siswa.create', compact('kelas'));
    }

    // Menyimpan data siswa baru
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
                'foto' => null,
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
            Log::error('Gagal menyimpan siswa: ' . $e->getMessage());

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
            Log::error('Gagal update siswa: ' . $e->getMessage());

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
            Log::error('Gagal hapus siswa: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Download template Excel
    // public function downloadTemplate()
    // {
    //     try {
    //         // Buat template sederhana
    //         $data = [
    //             ['NISN', 'NIS', 'Nama', 'Jenis_Kelamin', 'Email', 'No_Telp', 'Alamat', 'Kelas'],
    //             ['1234567890', '12345678', 'Contoh Siswa', 'L', 'contoh@email.com', '081234567890', 'Jl. Contoh No. 1', '10 IPA 1'],
    //             ['', '', '', '', '', '', '', ''],
    //             ['CATATAN:'],
    //             ['1. NISN harus 10 digit angka, NIS 8 digit angka'],
    //             ['2. Jenis Kelamin: L (Laki-laki) atau P (Perempuan)'],
    //             ['3. Kelas: Sesuaikan dengan nama kelas yang ada di sistem'],
    //             ['4. Email, No_Telp, Alamat bersifat optional'],
    //         ];

    //         $export = new \App\Exports\GenericExport($data, 'Template Import Siswa');
    //         $filename = 'template_import_siswa_' . date('Ymd_His') . '.xlsx';

    //         return Excel::download($export, $filename);

    //     } catch (\Exception $e) {
    //         Log::error('Error download template: ' . $e->getMessage());

    //         return redirect()->route('admin.siswa.create')
    //             ->with('info', 'Silakan upload file Excel yang sudah Anda miliki. Pastikan format kolom sesuai.');
    //     }
    // }

    // Import data dari Excel
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,xls,xlsx|max:5120'
        ]);

        try {
            set_time_limit(300); // 5 menit untuk file besar
            Excel::import(new SiswaImport, $request->file('file'));

            return redirect()->route('admin.siswa.index')->with('success', '✅ Data siswa berhasil diimport.');
        } catch (\Exception $e) {
            Log::error('Import Excel error: ' . $e->getMessage());
            return redirect()->back()->with('error', '❌ Gagal import: ' . $e->getMessage());
        }
    }

    // DOWNLOAD TEMPLATE EXCEL
    public function downloadTemplate()
    {
        // Template sederhana dengan header yang diharapkan
        // No, Nama Siswa, NIS, NISN, JK, TTL, Agama, Tingkat, Kelas, Edit
        // TTL format: "Kota, DD-MM-YYYY"
        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');

            // Header
            fputcsv($handle, ['No', 'Nama Siswa', 'NIS', 'NISN', 'JK', 'TTL', 'Agama', 'Tingkat', 'Kelas']);

            // Contoh Data 1
            fputcsv($handle, [
                '1',
                'Contoh Siswa 1',
                '12345',
                '0012345678',
                'L',
                'Bantul, 12-05-2010',
                'Islam',
                '7',
                '7A'
            ]);

            // Contoh Data 2
            fputcsv($handle, [
                '2',
                'Contoh Siswa 2',
                '12346',
                '0012345679',
                'P',
                'Sleman, 20-11-2010',
                'Kristen',
                '7',
                '7B'
            ]);

            fclose($handle);
        }, 'template_siswa_v2.csv');
    }
}
