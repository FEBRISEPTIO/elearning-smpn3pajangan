<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengumpulanTugas extends Model
{
    use HasFactory;

    protected $table = 'pengumpulan_tugas';

    protected $fillable = [
        'tugas_id',
        'nisn',
        'file',
        'keterangan',
        'status',
        'nilai',
        'feedback',
        'submitted_at'
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'nilai' => 'decimal:2',
    ];

    /**
     * Relasi ke tugas
     */
    public function tugas()
    {
        return $this->belongsTo(Tugas::class, 'tugas_id');
    }

    /**
     * Relasi ke siswa
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'nisn');
    }

    /**
     * Accessor untuk nama file
     */
    public function getFileNameAttribute()
    {
        return basename($this->file);
    }

    /**
     * Accessor untuk ekstensi file
     */
    public function getFileExtensionAttribute()
    {
        return pathinfo($this->file, PATHINFO_EXTENSION);
    }

    /**
     * Accessor untuk status dengan badge
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'submitted' => ['bg-blue-100', 'text-blue-800', 'Terkirim'],
            'late' => ['bg-red-100', 'text-red-800', 'Terlambat'],
            'graded' => ['bg-green-100', 'text-green-800', 'Dinilai'],
        ];

        return $badges[$this->status] ?? ['bg-gray-100', 'text-gray-800', 'Unknown'];
    }
}