<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('dokumen', function (Blueprint $table) {
            $table->id();
            $table->string('judul_dokumen', 50);
            $table->text('isi_dokumen');
            $table->date('tanggal_pembuatan');
            $table->string('status_dokumen', 50);
            $table->foreignId('id_ormawa')->constrained('ormawa')->onDelete('cascade');
            $table->foreignId('id_dosen')->constrained('dosen')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dokumen');
    }
};
