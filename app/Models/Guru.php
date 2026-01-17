<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;

    protected $table = 'guru'; // Explicitly set table name
    protected $primaryKey = 'nik';

    protected $fillable = [
        'user_id',
        'nik',
        'nama',
        'email',
        'jnsklmn',
        'no_telp',
        'foto'
    ];

    
    public function akun()
{
    return $this->belongsTo(Akun::class, 'nik', 'user_id');
}
    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }

    public function mapelKelas()
    {
        return $this->hasMany(MapelKelas::class, 'guru_nik', 'nik');
    }

    public function materi()
    {
        return $this->hasMany(Materi::class, 'guru_nik', 'nik');
    }

    public function tugas()
    {
        return $this->hasMany(Tugas::class, 'guru_id');
    }

    public function nilais()
    {
        return $this->hasMany(Nilai::class, 'guru_id');
    }
}