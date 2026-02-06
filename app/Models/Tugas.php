<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    use HasFactory;

    protected $table = 'tugas';
    protected $primaryKey = 'id';

    // Sesuai dengan field di database
    protected $fillable = [
        'judul_tugas',
        'deskripsi',
        'tugas',        // field untuk konten/instruksi tugas
        'deadline',
        'mapel_id',
        'kelas_id',
        'guru_nip'
        // Note: field 'file' tidak ada di database Anda, jadi kita skip
    ];

    protected $casts = [
        'deadline' => 'datetime',
    ];

    // Relasi ke kelas
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    // Relasi ke mata pelajaran
    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'mapel_id');
    }

    // Relasi ke guru
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_nip', 'nip');
    }

    // Relasi ke komentar
    public function komentar()
    {
        return $this->hasMany(Komentar::class, 'tugas_id');
    }

    // Relasi ke pengumpulan
    public function pengumpulan()
    {
        return $this->hasMany(PengumpulanTugas::class, 'tugas_id');
    }

    // Accessor untuk status deadline
    public function getStatusAttribute()
    {
        return now()->greaterThan($this->deadline) ? 'selesai' : 'aktif';
    }

    // Scope untuk tugas aktif
    public function scopeAktif($query)
    {
        return $query->where('deadline', '>', now());
    }

    // Scope untuk tugas selesai
    public function scopeSelesai($query)
    {
        return $query->where('deadline', '<=', now());
    }

    // Scope untuk tugas milik guru tertentu
    public function scopeMilikGuru($query, $guruNip)
    {
        return $query->where('guru_nip', $guruNip);
    }
}
