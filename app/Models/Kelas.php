<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $fillable = [
        'kelas'
    ];

    // Relasi ke siswa
    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'kelas_id');
    }

    // Relasi ke mapel_kelas
    public function mapelKelas()
    {
        return $this->hasMany(MapelKelas::class, 'kelas_id');
    }

    // Relasi ke tugas_kelas
    public function tugasKelas()
    {
        return $this->hasMany(TugasKelas::class, 'kelas_id');
    }
}