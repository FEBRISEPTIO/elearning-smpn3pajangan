<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    use HasFactory;

    protected $table = 'mata_pelajaran';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nama_mapel'
    ];

    /**
     * Relasi dengan mapel_kelas
     */
    public function mapelKelas()
    {
        return $this->hasMany(MapelKelas::class, 'mapel_id');
    }

    /**
     * Relasi dengan materi
     */
    public function materis()
    {
        return $this->hasMany(Materi::class, 'mapel_id', 'id');
    }

    /**
     * Relasi dengan tugas
     */
    public function tugas()
    {
        return $this->hasMany(Tugas::class, 'mata_pelajaran_id');
    }
}