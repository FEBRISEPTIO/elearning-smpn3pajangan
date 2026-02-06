<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\Akun;
use App\Models\Kelas;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SiswaImport implements ToCollection, WithHeadingRow, WithStartRow, WithChunkReading
{
    /**
     * Baris data dimulai setelah header.
     * Karena header di row 2, maka data mulai dari row 3.
     * @return int
     */
    public function startRow(): int
    {
        return 3;
    }

    /**
     * Header berada di baris ke-2.
     * @return int
     */
    public function headingRow(): int
    {
        return 2;
    }

    /**
     * Ukuran chunk untuk membaca file besar.
     * @return int
     */
    public function chunkSize(): int
    {
        return 100;
    }

    /**
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        // Debug: Log jumlah baris yang dibaca
        Log::info("Import Siswa: Memproses " . $rows->count() . " baris data.");

        if ($rows->count() > 0) {
            Log::info("Import Siswa: Header/Keys ditemukan: " . json_encode($rows->first()->keys()->toArray()));
        }

        foreach ($rows as $index => $row) {
            try {
                // Skip baris kosong
                if ($row->filter()->isEmpty()) {
                    continue;
                }

                // 1. Normalisasi Header / Ambil Data dengan Fuzzy Matching
                $nisn = $this->getValue($row, ['nisn', 'nomor induk siswa nasional']);
                $nis = $this->getValue($row, ['nis', 'nomor induk siswa']);
                $nama = $this->getValue($row, ['nama', 'nama siswa', 'nama lengkap']);
                $jenisKelaminRaw = $this->getValue($row, ['jk', 'jenis kelamin', 'sex']);
                $kelasRaw = $this->getValue($row, ['kelas', 'rombel', 'nama kelas']);
                $ttlRaw = $this->getValue($row, ['ttl', 'tempat tanggal lahir']);
                $agama = $this->getValue($row, ['agama', 'religion']);
                $tingkat = $this->getValue($row, ['tingkat', 'level']);

                // Validasi dasar: NISN, NIS, Nama wajib ada
                if (!$nisn || !$nis || !$nama) {
                    Log::warning("Import Siswa: Baris " . ($index + 3) . " dilewati karena data tidak lengkap (NISN/NIS/Nama).");
                    continue;
                }

                // 2. Parsing Data
                // Jenis Kelamin
                $jnsklmn = 'L'; // Default
                if ($jenisKelaminRaw) {
                    $jk = strtoupper(substr($jenisKelaminRaw, 0, 1));
                    $jnsklmn = in_array($jk, ['L', 'P']) ? $jk : 'L';
                }

                // TTL (Tempat, Tanggal Lahir)
                $tempatLahir = null;
                $tanggalLahir = null;
                if ($ttlRaw) {
                    // Format umum: "Bantul, 12-02-2013" atau "Jakarta, 2014-05-20"
                    $parts = explode(',', $ttlRaw);
                    if (count($parts) >= 2) {
                        $tempatLahir = trim($parts[0]);
                        $tglString = trim($parts[1]);

                        // Coba parsing tanggal
                        try {
                            // Asumsi format DD-MM-YYYY atau YYYY-MM-DD
                            $date = date_create($tglString);
                            if ($date) {
                                $tanggalLahir = date_format($date, 'Y-m-d');
                            }
                        } catch (\Exception $e) {
                            Log::warning("Import Siswa: Gagal parse tanggal lahir '{$tglString}'");
                        }
                    } else {
                        // Jika tidak ada koma, anggap tempat lahir saja
                        $tempatLahir = trim($ttlRaw);
                    }
                }

                // Kelas - Auto-create jika belum ada
                $kelasId = null;
                if ($kelasRaw) {
                    // Normalisasi nama kelas (hapus spasi berlebih)
                    $kelasNama = trim($kelasRaw);

                    // firstOrCreate: cari berdasarkan nama, jika tidak ada buat baru
                    $kelas = Kelas::firstOrCreate(
                        ['kelas' => $kelasNama] // Kondisi pencarian
                    );

                    $kelasId = $kelas->id;

                    if ($kelas->wasRecentlyCreated) {
                        Log::info("Import Siswa: Kelas '{$kelasNama}' berhasil dibuat (ID: {$kelasId})");
                    }
                }

                // 3. Simpan ke Database

                // Cek apakah siswa sudah ada - jika ada, SKIP (tidak update)
                $siswaExists = Siswa::where('nisn', $nisn)->exists();

                if ($siswaExists) {
                    // Skip - NISN sudah ada di database
                    Log::info("Import Siswa: NISN {$nisn} sudah ada, dilewati.");
                    continue;
                }

                // Create Baru (hanya jika NISN belum ada)
                $siswa = Siswa::create([
                    'nisn' => $nisn,
                    'nis' => $nis,
                    'nama' => $nama,
                    'jnsklmn' => $jnsklmn,
                    'kelas_id' => $kelasId,
                    'tempat_lahir' => $tempatLahir,
                    'tanggal_lahir' => $tanggalLahir,
                    'agama' => $agama,
                    'tingkat' => $tingkat,
                    'email' => null,
                    'no_telp' => null,
                    'alamat' => null,
                ]);

                // Buat Akun Login
                Akun::create([
                    'username' => $nisn,
                    'password' => Hash::make($nis), // Password default = NIS
                    'user_type' => 'siswa',
                    'user_id' => $siswa->nisn,
                    'role_id' => 3, // Siswa
                ]);
            } catch (\Exception $e) {
                Log::error("Import Siswa Error pada baris " . ($index + 3) . ": " . $e->getMessage());
            }
        }
    }

    /**
     * Helper untuk mengambil value dengan pencarian key fuzzy
     * 
     * @param Collection $row data baris
     * @param array $possibleKeys kunci yang mungkin (lowercase)
     * @return mixed value atau null
     */
    private function getValue($row, array $possibleKeys)
    {
        // $row adalah Collection dengan key = header column (slugified/lowercase)
        // Maatwebsite Excel biasanya men-slugify header, misal "Nama Siswa" jadi "nama_siswa"

        $keys = $row->keys();

        foreach ($possibleKeys as $pkey) {
            // Normalisasi key yang dicari (hapus spasi, lowercase)
            $normalizedPKey = Str::slug($pkey, '_'); // nama siswa -> nama_siswa

            // Cek exact match atau contains
            foreach ($keys as $k) {
                // Header excel dari library sudah slugified
                if ($k === $normalizedPKey) {
                    return $row[$k];
                }

                // Coba match partial jika perlu (hati-hati bisa false positive)
                // if (str_contains($k, $normalizedPKey)) {
                //     return $row[$k];
                // }
            }
        }

        return null;
    }
}
