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
            'file' => 'required|mimes:csv,txt|max:5120'
        ]);

        $file = $request->file('file');

        DB::beginTransaction();

        try {
            // Baca file CSV
            $csvData = $this->readCSV($file->getPathname());

            if (empty($csvData)) {
                return redirect()->back()->with('error', 'File CSV kosong');
            }

            $headers = array_map('strtolower', $csvData[0]);
            $rows = array_slice($csvData, 1);

            $successCount = 0;
            $errors = [];

            foreach ($rows as $index => $row) {
                $rowNumber = $index + 2;

                try {
                    // Skip baris kosong
                    if (empty(array_filter($row))) {
                        continue;
                    }

                    // Gabung header dengan data
                    $data = array_combine($headers, $row);

                    // Proses data
                    $result = $this->processSiswaRow($data, $rowNumber);

                    if ($result['success']) {
                        $successCount++;
                    } else {
                        $errors[] = $result['error'];
                    }

                } catch (\Exception $e) {
                    $errors[] = "Baris {$rowNumber}: " . $e->getMessage();
                }
            }

            DB::commit();

            // Tampilkan hasil
            if ($successCount > 0) {
                $message = "✅ Berhasil mengimport {$successCount} data siswa.";
                if (!empty($errors)) {
                    session()->flash('import_errors', array_slice($errors, 0, 10));
                }
                return redirect()->route('admin.siswa.index')->with('success', $message);
            } else {
                return redirect()->back()->with(
                    'error',
                    '❌ Tidak ada data yang berhasil diimport. ' .
                    (empty($errors) ? '' : 'Error: ' . implode(', ', array_slice($errors, 0, 3)))
                );
            }

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Import CSV error: ' . $e->getMessage());
            return redirect()->back()->with('error', '❌ Gagal import: ' . $e->getMessage());
        }
    }

    // BACA FILE CSV
    private function readCSV($filePath)
    {
        $data = [];

        if (($handle = fopen($filePath, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                $data[] = array_map('trim', $row);
            }
            fclose($handle);
        }

        return $data;
    }

    // PROSES SATU BARIS DATA
    private function processSiswaRow($data, $rowNumber)
    {
        // Ambil data
        $nisn = $data['nisn'] ?? $data['nisn_siswa'] ?? '';
        $nis = $data['nis'] ?? $data['nomor_induk'] ?? '';
        $nama = $data['nama'] ?? $data['nama_siswa'] ?? $data['nama_lengkap'] ?? '';
        $email = $data['email'] ?? '';
        $noTelp = $data['notelp'] ?? $data['telepon'] ?? $data['nohp'] ?? '';
        $alamat = $data['alamat'] ?? '';
        $jenisKelamin = $data['jeniskelamin'] ?? $data['jk'] ?? 'L';
        $kelas = $data['kelas'] ?? $data['nama_kelas'] ?? '';

        // Validasi wajib
        if (empty($nisn) || empty($nis) || empty($nama)) {
            return [
                'success' => false,
                'error' => "Baris {$rowNumber}: NISN/NIS/Nama tidak boleh kosong"
            ];
        }

        // Validasi format NISN (10 digit)
        if (!preg_match('/^\d{10}$/', $nisn)) {
            return [
                'success' => false,
                'error' => "Baris {$rowNumber}: NISN harus 10 digit angka"
            ];
        }

        // Validasi format NIS (8 digit)
        if (!preg_match('/^\d{8}$/', $nis)) {
            return [
                'success' => false,
                'error' => "Baris {$rowNumber}: NIS harus 8 digit angka"
            ];
        }

        // Cek duplikasi
        if (Siswa::where('nisn', $nisn)->exists()) {
            return [
                'success' => false,
                'error' => "Baris {$rowNumber}: NISN {$nisn} sudah terdaftar"
            ];
        }

        // Normalize jenis kelamin
        $jnsklmn = strtoupper(substr($jenisKelamin, 0, 1));
        if (!in_array($jnsklmn, ['L', 'P'])) {
            $jnsklmn = 'L';
        }

        // Cari kelas
        $kelasModel = Kelas::where('kelas', 'like', "%{$kelas}%")
            ->orWhere('nama_kelas', 'like', "%{$kelas}%")
            ->first();

        if (!$kelasModel) {
            return [
                'success' => false,
                'error' => "Baris {$rowNumber}: Kelas '{$kelas}' tidak ditemukan"
            ];
        }

        // Simpan siswa
        $siswa = Siswa::create([
            'nisn' => $nisn,
            'nis' => $nis,
            'nama' => $nama,
            'email' => $email ?: null,
            'no_telp' => $noTelp ?: null,
            'alamat' => $alamat ?: null,
            'jnsklmn' => $jnsklmn,
            'kelas_id' => $kelasModel->id,
            'foto' => null,
        ]);

        // Buat akun login
        Akun::create([
            'username' => $nisn,
            'password' => Hash::make($nis),
            'user_type' => 'siswa',
            'user_id' => $siswa->nisn,
            'role_id' => 3,
        ]);

        Log::info("Imported siswa: {$nama} ({$nisn})");

        return ['success' => true];
    }

    // DOWNLOAD TEMPLATE CSV
    public function downloadTemplate()
    {
        $csv = "nisn,nis,nama,email,notelp,alamat,jeniskelamin,kelas\n";
        $csv .= "1234567890,12345678,Contoh Siswa 1,siswa1@email.com,081234567890,Jl. Contoh No.1,L,10 IPA 1\n";
        $csv .= "2234567891,22345678,Contoh Siswa 2,siswa2@email.com,081298765432,Jl. Contoh No.2,P,11 IPA 2";

        $filename = 'template_siswa_' . date('Ymd_His') . '.csv';

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename={$filename}");
    }

}