<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Akun extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'akun';

    protected $fillable = [
        'username',
        'password',
        'user_type',
        'user_id',
        'role_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Tambahkan ini untuk authentication
    public function getAuthPassword()
    {
        return $this->password;
    }

    // Relasi ke role
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    // Relasi ke siswa
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'user_id', 'nisn');
    }

    // Relasi ke guru
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'user_id', 'nik');
    }

    // Relasi ke admin
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'user_id');
    }

    // Get the user's actual model based on user_type
    // PERBAIKAN: ganti nama method dari user() menjadi userDetail()
    public function userDetail()
    {
        if ($this->user_type === 'siswa') {
            return $this->siswa;
        } elseif ($this->user_type === 'guru') {
            return $this->guru;
        } elseif ($this->user_type === 'admin') {
            return $this->admin;
        }

        return null;
    }
}