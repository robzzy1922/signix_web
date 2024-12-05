<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('dokumens', function (Blueprint $table) {
            $table->float('qr_position_x')->nullable();
            $table->float('qr_position_y')->nullable();
            $table->float('qr_width')->nullable();
            $table->float('qr_height')->nullable();
        });
    }

    public function down()
    {
        Schema::table('dokumens', function (Blueprint $table) {
            $table->dropColumn(['qr_position_x', 'qr_position_y', 'qr_width', 'qr_height']);
        });
    }
};