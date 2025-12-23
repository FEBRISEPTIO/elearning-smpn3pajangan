<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    // Tentukan nama tabel secara eksplisit
    protected $table = 'roles';

    protected $fillable = [
        'nama_role'
    ];

    // Relasi ke akun
    public function akun()
    {
        return $this->hasMany(Akun::class, 'role_id');
    }
}