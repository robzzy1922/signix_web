<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('dosen', function (Blueprint $table) {
            $table->id();
            $table->string('nama_dosen', 50);
            $table->string('nip', 20)->unique();
            $table->string('email', 50)->unique();
            $table->string('password');
            $table->string('no_hp', 20);
            $table->string('prodi', 50);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dosen');
    }
};
