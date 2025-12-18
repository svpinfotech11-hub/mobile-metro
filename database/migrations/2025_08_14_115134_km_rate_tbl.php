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
         Schema::create('km_rate_tb', function (Blueprint $table) {
            $table->id();
            $table->string('from_km');
            $table->string('to_km');
            $table->string('km_rate');
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
