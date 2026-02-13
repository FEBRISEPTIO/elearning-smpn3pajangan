<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    // Tentukan nama tabel secara eksplisit
    protected $table = 'nilai';

    protected $fillable = [
        'nisn',
        'guru_nip',
        'mapel_id',
        'tugas_id',
        'nilai',
        'keterangan',
        'tgl_penilaian'
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'nisn', 'nisn');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_nip', 'nip');
    }

    public function mapel()
    {
        return $this->belongsTo(MataPelajaran::class, 'mapel_id');
    }

    public function tugas()
    {
        return $this->belongsTo(Tugas::class, 'tugas_id');
    }

    public function pengumpulan()
    {
        return $this->hasOne(PengumpulanTugas::class, 'tugas_id', 'tugas_id')
            ->where('nisn', $this->nisn);
    }
}
