<?php

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kemahasiswaan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kemahasiswaan', 50);
            $table->string('nip', 20)->unique();
            $table->string('email', 50)->unique();
            $table->string('no_hp', 20);
            $table->string('prodi', 50);
            $table->string('password');
            $table->string('profile')->nullable();
            $table->rememberToken(); // Tambahkan ini
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kemahasiswaans');
    }
};