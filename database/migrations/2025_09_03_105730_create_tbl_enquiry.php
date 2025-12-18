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
        Schema::create('tbl_enquiry', function (Blueprint $table) {
            $table->id(); // primary key
            $table->string('order_no')->unique();
            $table->string('customer_id');
            $table->string('pickup_location');
            $table->string('drop_location');
            $table->string('flat_shop_no')->nullable();
            $table->dateTime('shipping_date_time');
            $table->string('floor_number')->nullable();
            $table->boolean('pickup_services_lift')->default(false);
            $table->boolean('drop_services_lift')->default(false);

            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_enquiry');
    }
};
