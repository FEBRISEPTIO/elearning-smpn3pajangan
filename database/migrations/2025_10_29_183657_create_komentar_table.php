<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('komentar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('user_type')->nullable(); // jika menggunakan polymorphic
            $table->foreignId('tugas_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('materi_id')->nullable()->constrained()->onDelete('cascade');
            $table->text('isi_komentar');
            $table->timestamps();

            // Index untuk performa query
            $table->index(['materi_id', 'tugas_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('komentar');
    }
};