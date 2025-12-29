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
        Schema::create('user_enquiries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('sub_category_id');

            $table->unsignedBigInteger('service_id')->nullable();
            $table->unsignedBigInteger('product_subcategory_id')->nullable();

            // common fields
            $table->string('service_name')->nullable();
            $table->string('pickup_location')->nullable();
            $table->string('drop_location')->nullable();
            $table->string('service_location')->nullable();
            $table->string('floor_number')->nullable();
            $table->date('date')->nullable();
            $table->string('vehicle_number')->nullable();
            $table->text('service_description')->nullable();
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
        Schema::dropIfExists('user_enquiries');
    }
};
