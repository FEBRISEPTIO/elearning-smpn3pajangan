<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    protected $table = 'admin';

    protected $fillable = [
        'nama',
        'email',
        'jnsklmn',
        'alamat',
        'no_telp',
        'foto'
    ];

    // Relasi ke akun
    public function akun()
    {
        return $this->hasOne(Akun::class, 'user_id');
    }
}