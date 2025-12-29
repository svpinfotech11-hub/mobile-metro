<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // OTP verification fields
            $table->boolean('is_verified')
                  ->default(false)
                  ->after('role');

            $table->timestamp('otp_expires_at')
                  ->nullable()
                  ->after('is_verified');

            $table->string('otp', 6)
                  ->nullable()
                  ->after('otp_expires_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'otp',
                'otp_expires_at',
                'is_verified'
            ]);
        });
    }
};
