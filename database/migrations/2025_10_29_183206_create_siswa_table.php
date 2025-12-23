<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('siswa', function (Blueprint $table) {
            $table->string('nisn', 20)->primary();
            $table->string('nis', 20)->nullable();
            $table->string('nama', 100);
            $table->string('email')->nullable(); // Tambahkan kolom email
            $table->enum('jnsklmn', ['L', 'P']);
            $table->text('alamat')->nullable();
            $table->string('no_telp', 15)->nullable();
            $table->string('foto')->nullable();
            $table->foreignId('kelas_id')->nullable()->constrained('kelas')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siswa');
    }
};