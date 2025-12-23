<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('materi_pembelajaran', function (Blueprint $table) {
            // Tambahkan kolom kelas_id setelah mapel_id
            $table->foreignId('kelas_id')
                ->after('mapel_id')
                ->nullable() // atau ->nullable() jika data lama tidak punya kelas_id
                ->constrained('kelas')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('materi_pembelajaran', function (Blueprint $table) {
            $table->dropForeign(['kelas_id']);
            $table->dropColumn('kelas_id');
        });
    }
};