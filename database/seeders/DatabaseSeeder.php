<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Seeder untuk roles
        DB::table('roles')->insert([
            ['nama_role' => 'Administrator', 'created_at' => now(), 'updated_at' => now()],
            ['nama_role' => 'Guru', 'created_at' => now(), 'updated_at' => now()],
            ['nama_role' => 'Siswa', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Seeder untuk kelas
        $kelas = ['X IPA 1', 'X IPA 2', 'X IPS 1', 'XI IPA 1', 'XI IPA 2', 'XI IPS 1', 'XII IPA 1', 'XII IPA 2', 'XII IPS 1'];
        foreach ($kelas as $nama_kelas) {
            DB::table('kelas')->insert([
                'kelas' => $nama_kelas,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Seeder untuk mata pelajaran
        $mapel = ['Matematika', 'Fisika', 'Kimia', 'Biologi', 'Bahasa Indonesia', 'Bahasa Inggris', 'Sejarah', 'Geografi', 'Ekonomi', 'Sosiologi'];
        foreach ($mapel as $nama_mapel) {
            DB::table('mata_pelajaran')->insert([
                'nama_mapel' => $nama_mapel,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Seeder untuk admin default
        DB::table('admin')->insert([
            'nama' => 'Administrator',
            'email' => 'admin@elearning.com',
            'jnsklmn' => 'L',
            'alamat' => 'Alamat admin',
            'no_telp' => '081234567890',
            'foto' => null,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Seeder untuk guru contoh
        DB::table('guru')->insert([
            [
                'nik' => 'G001',
                'nama' => 'Dr. Ahmad Susanto, M.Pd',
                'email' => 'ahmad.susanto@sekolah.com',
                'jnsklmn' => 'L',
                'alamat' => 'Jl. Pendidikan No. 123',
                'no_telp' => '081234567891',
                'foto' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nik' => 'G002',
                'nama' => 'Dewi Sartika, S.Pd',
                'email' => 'dewi.sartika@sekolah.com',
                'jnsklmn' => 'P',
                'alamat' => 'Jl. Guru No. 45',
                'no_telp' => '081234567892',
                'foto' => null,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // Seeder untuk akun admin
        DB::table('akun')->insert([
            'username' => 'admin',
            'password' => Hash::make('password'),
            'user_type' => 'admin',
            'user_id' => '1', // ID admin
            'role_id' => 1, // Administrator
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Seeder untuk akun guru
        DB::table('akun')->insert([
            [
                'username' => 'G001',
                'password' => Hash::make('G001'), // Password = NIK
                'user_type' => 'guru',
                'user_id' => 'G001', // NIK guru
                'role_id' => 2, // Guru
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'username' => 'G002',
                'password' => Hash::make('G002'), // Password = NIK
                'user_type' => 'guru',
                'user_id' => 'G002', // NIK guru
                'role_id' => 2, // Guru
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // HAPUS: Bagian data siswa dihapus karena sekarang didaftarkan oleh admin
        // Data siswa akan ditambahkan melalui sistem oleh admin
    }
}