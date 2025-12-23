<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tugas', function (Blueprint $table) {
            $table->id();
            $table->string('judul_tugas', 255);
            $table->text('deskripsi')->nullable();
            $table->text('tugas');
            $table->dateTime('deadline')->nullable();
            $table->foreignId('mapel_id')->constrained('mata_pelajaran')->onDelete('cascade');
            $table->string('guru_nik', 20);
            $table->timestamps();

            $table->foreign('guru_nik')->references('nik')->on('guru')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tugas');
    }
};