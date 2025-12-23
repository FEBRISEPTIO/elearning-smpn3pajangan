<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapelKelas extends Model
{
    use HasFactory;

    protected $table = 'mapel_kelas';

    protected $fillable = [
        'mapel_id',
        'kelas_id',
        'guru_nik'
    ];

    // Relasi ke mata pelajaran
    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'mapel_id');
    }

    // Relasi ke kelas
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    // Relasi ke guru
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_nik', 'nik');
    }
}