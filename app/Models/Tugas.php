<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    use HasFactory;

    protected $table = 'tugas';

    protected $fillable = [
        'judul_tugas',
        'deskripsi',
        'tugas',
        'deadline',
        'mapel_id',
        'kelas_id',
        'guru_nik'
    ];

    protected $casts = [
        'deadline' => 'datetime',
    ];

    /**
     * Relasi ke kelas
     */
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    /**
     * Relasi ke mata pelajaran
     */
    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'mapel_id');
    }

    /**
     * Relasi ke guru
     */
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_nik', 'nik');
    }

    /**
     * Relasi ke komentar (khusus tugas)
     */
    public function komentar()
    {
        return $this->hasMany(Komentar::class, 'tugas_id')->whereNull('materi_id');
    }

    /**
     * Relasi ke pengumpulan tugas
     */
    public function pengumpulan()
    {
        return $this->hasMany(PengumpulanTugas::class, 'tugas_id');
    }

    /**
     * Accessor untuk jumlah komentar
     */
    public function getKomentarCountAttribute()
    {
        if ($this->relationLoaded('komentar')) {
            return $this->komentar->count();
        }
        return $this->komentar()->count();
    }

    /**
     * Accessor untuk cek apakah deadline sudah lewat
     */
    public function getIsDeadlinePassedAttribute()
    {
        return now()->greaterThan($this->deadline);
    }

    /**
     * Accessor untuk sisa waktu deadline
     */
    public function getTimeRemainingAttribute()
    {
        return now()->diff($this->deadline);
    }

    /**
     * Scope untuk tugas aktif (belum lewat deadline)
     */
    public function scopeAktif($query)
    {
        return $query->where('deadline', '>', now());
    }

    /**
     * Scope untuk tugas yang sudah lewat deadline
     */
    public function scopeLewatDeadline($query)
    {
        return $query->where('deadline', '<=', now());
    }
}