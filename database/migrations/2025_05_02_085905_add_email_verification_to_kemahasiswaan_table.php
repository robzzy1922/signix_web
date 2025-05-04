<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('kemahasiswaan', callback: function (Blueprint $table) {
            $table->boolean('is_email_verified')->default(false)->after('email');
            $table->string('email_verification_code')->nullable()->after('is_email_verified');
            $table->timestamp('email_verification_expires_at')->nullable()->after('email_verification_code');
            $table->timestamp('email_verified_at')->nullable()->after('email_verification_expires_at');
            $table->string('verification_email')->nullable()->after('email_verified_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kemahasiswaan', function (Blueprint $table) {
            $table->dropColumn([
                'is_email_verified',
                'email_verification_code',
                'email_verification_expires_at',
                'email_verified_at',
                'verification_email'
            ]);
        });
    }
};