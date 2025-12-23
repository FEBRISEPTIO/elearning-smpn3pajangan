<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('nilai', function (Blueprint $table) {
            $table->id();
            $table->string('nisn', 20);
            $table->string('guru_nik', 20);
            $table->foreignId('mapel_id')->constrained('mata_pelajaran')->onDelete('cascade');
            $table->foreignId('tugas_id')->nullable()->constrained('tugas')->onDelete('set null');
            $table->integer('nilai')->nullable();
            $table->string('keterangan', 255)->nullable();
            $table->dateTime('tgl_penilaian')->useCurrent();
            $table->timestamps();

            $table->foreign('nisn')->references('nisn')->on('siswa')->onDelete('cascade');
            $table->foreign('guru_nik')->references('nik')->on('guru')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nilai');
    }
};