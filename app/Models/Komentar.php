<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Komentar extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_type',
        'tugas_id',
        'materi_id',
        'isi_komentar'
    ];

    protected $table = 'komentar';

    /**
     * Relasi ke materi
     */
    public function materi()
    {
        return $this->belongsTo(Materi::class, 'materi_id');
    }

    /**
     * Relasi ke tugas
     */
    public function tugas()
    {
        return $this->belongsTo(Tugas::class, 'tugas_id');
    }

    /**
     * Relasi ke user (User model yang umum)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke guru (jika user_type = 'guru')
     */
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'user_id', 'nip');
    }

    /**
     * Relasi ke siswa (jika user_type = 'siswa')
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'user_id');
    }

    /**
     * Scope untuk komentar materi
     */
    public function scopeForMateri($query)
    {
        return $query->whereNotNull('materi_id')->whereNull('tugas_id');
    }

    /**
     * Scope untuk komentar tugas
     */
    public function scopeForTugas($query)
    {
        return $query->whereNotNull('tugas_id')->whereNull('materi_id');
    }

    /**
     * Get user name berdasarkan user_type
     */
    public function getUserNameAttribute()
    {
        if ($this->user_type === 'guru' && $this->guru) {
            return $this->guru->nama ?? 'Guru';
        } elseif ($this->user_type === 'siswa' && $this->siswa) {
            return $this->siswa->nama ?? 'Siswa';
        } elseif ($this->user) {
            return $this->user->name ?? 'Pengguna';
        }

        return 'Pengguna';
    }

    /**
     * Get user role name
     */
    public function getUserRoleAttribute()
    {
        return $this->user_type === 'guru' ? 'Guru' : ($this->user_type === 'siswa' ? 'Siswa' : 'Pengguna');
    }

    /**
     * Get user profile info
     */
    public function getUserProfile()
    {
        if ($this->user_type === 'guru') {
            return [
                'type' => 'guru',
                'name' => $this->guru->nama ?? $this->user->name ?? 'Guru',
                'avatar' => 'fas fa-chalkboard-teacher',
                'color' => 'bg-blue-100 text-blue-800',
                'icon_color' => 'text-blue-600'
            ];
        } elseif ($this->user_type === 'siswa') {
            return [
                'type' => 'siswa',
                'name' => $this->siswa->nama ?? $this->user->name ?? 'Siswa',
                'kelas' => $this->siswa->kelas->kelas ?? null,
                'avatar' => 'fas fa-user-graduate',
                'color' => 'bg-gray-100 text-gray-800',
                'icon_color' => 'text-gray-600'
            ];
        }

        return [
            'type' => 'unknown',
            'name' => 'Pengguna',
            'avatar' => 'fas fa-user',
            'color' => 'bg-gray-100 text-gray-800',
            'icon_color' => 'text-gray-600'
        ];
    }
}
