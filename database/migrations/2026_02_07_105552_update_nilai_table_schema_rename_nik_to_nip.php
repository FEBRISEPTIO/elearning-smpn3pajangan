<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('nilai', function (Blueprint $table) {
            // Find and drop foreign key for guru_nik
            $foreignKeys = \Illuminate\Support\Facades\DB::select("
                SELECT CONSTRAINT_NAME 
                FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE()
                  AND TABLE_NAME = 'nilai' 
                  AND COLUMN_NAME = 'guru_nik' 
                  AND CONSTRAINT_NAME LIKE '%foreign%'
            ");

            foreach ($foreignKeys as $fk) {
                $table->dropForeign($fk->CONSTRAINT_NAME);
            }
        });

        // Use raw SQL for rename to be more compatible with older MariaDB/MySQL
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE nilai CHANGE guru_nik guru_nip VARCHAR(20)");

        Schema::table('nilai', function (Blueprint $table) {
            // Add new foreign key
            $table->foreign('guru_nip')->references('nip')->on('guru')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('nilai', function (Blueprint $table) {
            $table->dropForeign(['guru_nip']);
        });

        \Illuminate\Support\Facades\DB::statement("ALTER TABLE nilai CHANGE guru_nip guru_nik VARCHAR(20)");

        Schema::table('nilai', function (Blueprint $table) {
            $table->foreign('guru_nik')->references('nik')->on('guru')->onDelete('cascade');
        });
    }
};
