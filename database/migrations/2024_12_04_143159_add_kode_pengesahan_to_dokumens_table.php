<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKodePengesahanToDokumensTable extends Migration
{
    public function up()
    {
        Schema::table('dokumens', function (Blueprint $table) {
            $table->string('kode_pengesahan')->nullable();
        });
    }

    public function down()
    {
        Schema::table('dokumens', function (Blueprint $table) {
            $table->dropColumn('kode_pengesahan');
        });
    }
}