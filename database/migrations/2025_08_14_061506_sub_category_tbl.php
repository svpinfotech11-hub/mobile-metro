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
         Schema::create('sub_category_tbl', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id'); // foreign key column
            $table->string('sub_categoryname');
            $table->string('status')->default(1);
            $table->timestamps();
            $table->foreign('category_id')
            ->references('id')
            ->on('category_tbl') // your categories table name
            ->onDelete('cascade');
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
