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
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('business_name')->nullable();
            $table->text('address')->nullable();
            $table->string('mobile_no')->unique();
            $table->string('email')->nullable();
            $table->string('business_type')->nullable(); // e.g. JSON or CSV format
            $table->text('business_description')->nullable();
            $table->integer('experience_years')->nullable();
            $table->string('service_areas')->nullable();
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
        Schema::dropIfExists('vendors');
    }
};
