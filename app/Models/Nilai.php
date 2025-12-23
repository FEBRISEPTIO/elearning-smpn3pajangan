<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    // Tentukan nama tabel secara eksplisit
    protected $table = 'nilai';
    
    protected $fillable = [
        'siswa_id', 'materi_id', 'tugas_id', 'jenis', 'nilai', 'keterangan'
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function materi()
    {
        return $this->belongsTo(Materi::class, 'materi_id');
    }

    public function tugas()
    {
        return $this->belongsTo(Tugas::class, 'tugas_id');
    }
}