<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;

    protected $table = 'guru';
    protected $primaryKey = 'nip';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'nip',
        'nuptk',
        'nama',
        'email',
        'jnsklmn',
        'jenis_ptk',
        'no_telp',
        'foto'
    ];


    public function akun()
    {
        return $this->belongsTo(Akun::class, 'nip', 'user_id');
    }

    public function mapelKelas()
    {
        return $this->hasMany(MapelKelas::class, 'guru_nip', 'nip');
    }

    public function materi()
    {
        return $this->hasMany(Materi::class, 'guru_nip', 'nip');
    }

    public function tugas()
    {
        return $this->hasMany(Tugas::class, 'guru_nip', 'nip');
    }

    public function nilais()
    {
        return $this->hasMany(Nilai::class, 'guru_nip', 'nip');
    }
}
