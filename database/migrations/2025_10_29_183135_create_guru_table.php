<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('guru', function (Blueprint $table) {
            $table->string('nik', 20)->primary();
            $table->string('nama', 100);
            $table->string('email', 100)->unique();
            $table->enum('jnsklmn', ['L', 'P']);
            $table->text('alamat')->nullable();
            $table->string('no_telp', 15)->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guru');
    }
};