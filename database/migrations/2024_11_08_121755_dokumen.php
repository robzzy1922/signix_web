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
            $table->string('nomor_surat');
            $table->string('perihal');
            $table->enum('status_dokumen', ['diajukan', 'disahkan', 'direvisi'])->default('diajukan');
            $table->string('file');
            $table->string('keterangan')->nullable();
            $table->date('tanggal_pengajuan');
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
