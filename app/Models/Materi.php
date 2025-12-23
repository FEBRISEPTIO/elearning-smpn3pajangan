<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    use HasFactory;

    protected $table = 'materi_pembelajaran';

    protected $fillable = [
        'judul_materi',
        'deskripsi',
        'materi',
        'file',
        'kelas_id',
        'mapel_id',
        'guru_nik',
        'views',
        'downloads'
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
     * Relasi ke komentar
     */
    public function komentar()
    {
        return $this->hasMany(Komentar::class, 'materi_id')->whereNull('tugas_id');
    }

    /**
     * Accessor untuk jumlah komentar
     * PERBAIKAN: Ganti method dengan accessor
     */
    public function getKomentarCountAttribute()
    {
        // Gunakan eager loading count jika sudah di-load
        if ($this->relationLoaded('komentar')) {
            return $this->komentar->count();
        }

        // Atau hitung langsung dari database
        return $this->komentar()->count();
    }

    /**
     * Accessor untuk cek apakah ada file
     */
    public function getHasFileAttribute()
    {
        return !empty($this->file);
    }

    /**
     * Scope untuk materi dengan file
     */
    public function scopeWithFile($query)
    {
        return $query->whereNotNull('file');
    }

    /**
     * Scope untuk materi tanpa file
     */
    public function scopeWithoutFile($query)
    {
        return $query->whereNull('file');
    }
}