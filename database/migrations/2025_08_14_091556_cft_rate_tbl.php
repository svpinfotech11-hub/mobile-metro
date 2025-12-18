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
         Schema::create('cft_rate_tbl', function (Blueprint $table) {
            $table->id();
            $table->string('from_cft');
            $table->string('to_cft');
            $table->string('cft_rete');
            $table->string('rate_type');
            $table->string('appicable_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
