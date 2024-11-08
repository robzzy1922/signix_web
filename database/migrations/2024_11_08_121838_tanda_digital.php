<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tanda_digital', function (Blueprint $table) {
            $table->id();
            $table->string('data_qr');
            $table->date('tanggal_pembuatan');
            $table->foreignId('id_ormawa')->constrained('ormawa')->onDelete('cascade');
            $table->foreignId('id_dosen')->constrained('dosen')->onDelete('cascade');
            $table->foreignId('id_dokumen')->constrained('dokumen')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tanda_digital');
    }
};
