<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ormawa', function (Blueprint $table) {
            $table->id();
            $table->string('nama_mahasiswa', 50);
            $table->string('nama_ormawa', 50);
            $table->string('nim', 50)->unique();
            $table->string('email', 50)->unique();
            $table->string('password');
            $table->string('no_hp', 20);
            $table->string('profile_photo_path')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ormawa');
    }
};
