<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\Akun;
use App\Models\Kelas;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class SiswaImport implements ToCollection, WithHeadingRow
{
    private $errors = [];
    private $successCount = 0;
    private $previewData = [];
    public $onlyPreview = false;

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            try {
                $rowNumber = $index + 2; // +2 karena header row +1 index

                // Debug: Lihat struktur row
                Log::info("Row {$rowNumber} data: " . json_encode($row->toArray()));

                // Normalize column names
                $rowArray = $row->toArray();
                $normalizedRow = [];

                foreach ($rowArray as $key => $value) {
                    $normalizedKey = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $key));
                    $normalizedRow[$normalizedKey] = trim($value);
                }

                // Ambil nilai dengan berbagai kemungkinan nama kolom
                $nisn = $this->getValue($normalizedRow, ['nisn']);
                $nis = $this->getValue($normalizedRow, ['nis']);
                $nama = $this->getValue($normalizedRow, ['nama', 'namasiswa', 'namalengkap']);
                $email = $this->getValue($normalizedRow, ['email', 'surel']);
                $noTelp = $this->getValue($normalizedRow, ['notelp', 'telepon', 'nohp', 'handphone']);
                $alamat = $this->getValue($normalizedRow, ['alamat']);
                $jenisKelamin = $this->getValue($normalizedRow, ['jeniskelamin', 'jk', 'kelamin', 'gender']);
                $kelas = $this->getValue($normalizedRow, ['kelas', 'namakelas', 'tingkat']);

                // Validasi data wajib
                if (empty($nisn) || empty($nis) || empty($nama)) {
                    $this->errors[] = "Baris {$rowNumber}: Data tidak lengkap";
                    continue;
                }

                // Validasi format
                if (!preg_match('/^\d{10}$/', $nisn)) {
                    $this->errors[] = "Baris {$rowNumber}: NISN '{$nisn}' harus 10 digit angka";
                    continue;
                }

                if (!preg_match('/^\d{8}$/', $nis)) {
                    $this->errors[] = "Baris {$rowNumber}: NIS '{$nis}' harus 8 digit angka";
                    continue;
                }

                // Untuk preview mode, cukup kumpulkan data
                if ($this->onlyPreview) {
                    $this->previewData[] = [
                        'nisn' => $nisn,
                        'nis' => $nis,
                        'nama' => $nama,
                        'email' => $email,
                        'jk' => $jenisKelamin,
                        'kelas' => $kelas
                    ];
                    $this->successCount++;
                    continue;
                }

                // Cek duplikasi
                if (Siswa::where('nisn', $nisn)->exists()) {
                    $this->errors[] = "Baris {$rowNumber}: NISN '{$nisn}' sudah terdaftar";
                    continue;
                }

                if (Siswa::where('nis', $nis)->exists()) {
                    $this->errors[] = "Baris {$rowNumber}: NIS '{$nis}' sudah terdaftar";
                    continue;
                }

                // Cari kelas_id
                $kelas_id = $this->findKelasId($kelas);
                if (!$kelas_id) {
                    $this->errors[] = "Baris {$rowNumber}: Kelas '{$kelas}' tidak ditemukan";
                    continue;
                }

                // Normalize jenis kelamin
                $jnsklmn = $this->normalizeJenisKelamin($jenisKelamin);

                // Simpan data
                DB::transaction(function () use ($nisn, $nis, $nama, $email, $noTelp, $alamat, $jnsklmn, $kelas_id) {
                    // 1. Create siswa
                    $siswa = Siswa::create([
                        'nisn' => $nisn,
                        'nis' => $nis,
                        'nama' => $nama,
                        'email' => $email,
                        'no_telp' => $noTelp,
                        'alamat' => $alamat,
                        'jnsklmn' => $jnsklmn,
                        'kelas_id' => $kelas_id,
                        'foto' => null,
                    ]);

                    // 2. Create akun
                    Akun::create([
                        'username' => $nisn,
                        'password' => Hash::make($nis),
                        'user_type' => 'siswa',
                        'user_id' => $siswa->nisn,
                        'role_id' => 3,
                    ]);
                });

                $this->successCount++;

            } catch (\Exception $e) {
                $this->errors[] = "Baris " . ($index + 2) . ": " . $e->getMessage();
                Log::error('Import error baris ' . ($index + 2) . ': ' . $e->getMessage());
            }
        }
    }

    private function getValue($row, $possibleKeys)
    {
        foreach ($possibleKeys as $key) {
            if (isset($row[$key]) && !empty(trim($row[$key]))) {
                return trim($row[$key]);
            }
        }
        return null;
    }

    private function normalizeJenisKelamin($value)
    {
        if (empty($value))
            return 'L';

        $value = strtolower(trim($value));

        $mapping = [
            'l' => 'L',
            'laki-laki' => 'L',
            'lakilaki' => 'L',
            'laki' => 'L',
            'male' => 'L',
            'm' => 'L',
            'pria' => 'L',
            'p' => 'P',
            'perempuan' => 'P',
            'wanita' => 'P',
            'female' => 'P',
            'f' => 'P',
            'cewek' => 'P',
        ];

        return $mapping[$value] ?? 'L';
    }

    private function findKelasId($kelasName)
    {
        if (empty($kelasName)) {
            return Kelas::first()->id ?? null;
        }

        // Cari dengan berbagai pola
        return Kelas::where('kelas', 'like', "%{$kelasName}%")
            ->orWhere('nama_kelas', 'like', "%{$kelasName}%")
            ->orWhere('tingkat', 'like', "%{$kelasName}%")
            ->value('id');
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getSuccessCount()
    {
        return $this->successCount;
    }

    public function getPreviewData()
    {
        return $this->previewData;
    }
}