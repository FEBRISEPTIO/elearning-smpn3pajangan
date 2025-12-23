<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('materi_pembelajaran', function (Blueprint $table) {
            $table->id();
            $table->string('judul_materi', 255);
            $table->text('deskripsi')->nullable();
            $table->text('materi');
            $table->string('file')->nullable();
            $table->foreignId('mapel_id')->constrained('mata_pelajaran')->onDelete('cascade');
            $table->string('guru_nik', 20);
            $table->timestamps();

            $table->foreign('guru_nik')->references('nik')->on('guru')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materi_pembelajaran');
    }
};