<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('tugas', function (Blueprint $table) {
            // Tambahkan kolom kelas_id
            $table->foreignId('kelas_id')
                ->after('mapel_id')
                ->nullable()
                ->constrained('kelas')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('tugas', function (Blueprint $table) {
            $table->dropForeign(['kelas_id']);
            $table->dropColumn('kelas_id');
        });
    }
};