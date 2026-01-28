<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DataSekolah;
use Illuminate\Support\Facades\DB;

class DataSekolahSeeder extends Seeder
{
    public function run()
    {
        // Hapus data lama jika ada
        DB::table('data_sekolah')->delete();

        // Buat data baru
        DataSekolah::create([
            'nama_sekolah' => 'Sekolah Tinggi Ilmu Pendidikan dan Bahasa Asing (STIPA)',
            'logo' => null,
            'visi' => 'Menjadi institusi pendidikan tinggi yang unggul dalam menghasilkan pendidik dan tenaga profesional di bidang bahasa asing yang berdaya saing global.',
            'misi' => '1. Menyelenggarakan pendidikan yang berkualitas
2. Mengembangkan penelitian yang inovatif
3. Melaksanakan pengabdian kepada masyarakat
4. Mengembangkan kerjasama dengan berbagai institusi',
            'alamat' => 'Jl. Pendidikan No. 123, Kota Pendidikan, Jawa Barat',
            'telepon' => '(021) 1234-5678',
            'email' => 'info@stipa.ac.id',
            'website' => 'https://stipa.ac.id'
        ]);

        $this->command->info('Data sekolah berhasil ditambahkan!');
    }
}