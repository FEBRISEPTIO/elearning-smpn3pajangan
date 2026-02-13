<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswa';
    protected $primaryKey = 'nisn'; // Primary key adalah nisn
    public $incrementing = false; // Karena nisn string
    protected $keyType = 'string';

    protected $fillable = [
        'nisn',
        'nis',
        'nama',
        'email',
        'jnsklmn',
        'kelas_id',
        'alamat',
        'no_telp',
        'foto',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'tingkat'
    ];

    // Relasi dengan kelas
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    // Relasi dengan akun
    public function akun()
    {
        return $this->hasOne(Akun::class, 'user_id', 'nisn')
            ->where('user_type', 'siswa');
    }

    // Relasi lainnya (sesuaikan jika perlu)
    public function nilais()
    {
        return $this->hasMany(Nilai::class, 'nisn', 'nisn');
    }

    public function pengumpulanTugas()
    {
        return $this->hasMany(PengumpulanTugas::class, 'nisn', 'nisn');
    }

    public function komentars()
    {
        return $this->hasManyThrough(
            Komentar::class,
            Akun::class,
            'user_id', // FK on Akun table (stores nisn)
            'user_id', // FK on Komentar table (stores akun id)
            'nisn',    // Local key on Siswa table
            'id'       // Local key on Akun table
        );
    }

    public function tugas()
    {
        return $this->hasManyThrough(
            Tugas::class,
            Kelas::class,
            'id', // Foreign key pada kelas
            'kelas_id', // Foreign key pada tugas
            'kelas_id', // Local key pada siswa
            'id' // Local key pada kelas
        );
    }
}
