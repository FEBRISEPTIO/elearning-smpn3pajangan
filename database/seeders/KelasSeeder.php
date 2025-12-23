<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        $kelas = [
            ['kelas' => 'VII 1'],
            ['kelas' => 'VII 2'],
            ['kelas' => 'VII 3'],
            ['kelas' => 'VIII 1'],
            ['kelas' => 'VIII 2'],
            ['kelas' => 'VIII 3'],
            ['kelas' => 'IX 1'],
            ['kelas' => 'IX 2'],
            ['kelas' => 'IX 3'],
        ];

        foreach ($kelas as $data) {
            DB::table('kelas')->insert([
                'kelas' => $data['kelas'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}