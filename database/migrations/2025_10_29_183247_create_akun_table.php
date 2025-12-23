<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('akun', function (Blueprint $table) {
            $table->id();
            $table->string('username', 50)->unique();
            $table->string('password');
            $table->enum('user_type', ['admin', 'guru', 'siswa']);
            $table->string('user_id', 20); // id admin, nik guru, atau nisn siswa
            $table->foreignId('role_id')->nullable()->constrained('roles')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('akun');
    }
};