<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengumpulan_tugas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tugas_id');
            $table->unsignedBigInteger('nisn');
            $table->string('file')->nullable();
            $table->text('keterangan')->nullable();
            $table->enum('status', ['submitted', 'late', 'graded'])->default('submitted');
            $table->decimal('nilai', 5, 2)->nullable();
            $table->text('feedback')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('tugas_id')->references('id')->on('tugas')->onDelete('cascade');
            $table->foreign('siswa_id')->references('id')->on('siswa')->onDelete('cascade');

            // Unique constraint untuk mencegah pengumpulan ganda
            $table->unique(['tugas_id', 'siswa_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengumpulan_tugas');
    }
};