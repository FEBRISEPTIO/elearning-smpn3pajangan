<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataSekolah extends Model
{
    protected $table = 'data_sekolah';

    protected $fillable = [
        'nama_sekolah',
        'logo',
        'visi',
        'misi',
        'alamat',
        'telepon',
        'email',
        'website'
    ];

    // Method untuk mendapatkan data sekolah
    public static function getData()
    {
        return self::first();
    }
}