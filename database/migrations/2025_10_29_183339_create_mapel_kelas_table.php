<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('mapel_kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mapel_id')->constrained('mata_pelajaran')->onDelete('cascade');
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->string('guru_nik', 20)->nullable();
            $table->timestamps();

            $table->foreign('guru_nik')->references('nik')->on('guru')->onDelete('set null');
            $table->unique(['mapel_id', 'kelas_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mapel_kelas');
    }
};