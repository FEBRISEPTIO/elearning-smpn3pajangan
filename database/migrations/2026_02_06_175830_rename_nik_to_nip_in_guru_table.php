<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Use raw statements for better compatibility with older MariaDB/MySQL versions
        // Syntax: ALTER TABLE table_name CHANGE old_column new_column definition

        // guru table
        DB::statement('ALTER TABLE guru CHANGE nik nip VARCHAR(20)');

        // mapel_kelas table
        DB::statement('ALTER TABLE mapel_kelas CHANGE guru_nik guru_nip VARCHAR(20)');

        // materi_pembelajaran table
        DB::statement('ALTER TABLE materi_pembelajaran CHANGE guru_nik guru_nip VARCHAR(20)');

        // tugas table
        DB::statement('ALTER TABLE tugas CHANGE guru_nik guru_nip VARCHAR(20)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE guru CHANGE nip nik VARCHAR(20)');
        DB::statement('ALTER TABLE mapel_kelas CHANGE guru_nip guru_nik VARCHAR(20)');
        DB::statement('ALTER TABLE materi_pembelajaran CHANGE guru_nip guru_nik VARCHAR(20)');
        DB::statement('ALTER TABLE tugas CHANGE guru_nip guru_nik VARCHAR(20)');
    }
};
