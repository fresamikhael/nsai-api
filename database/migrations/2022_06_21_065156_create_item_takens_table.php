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
        Schema::create('item_takens', function (Blueprint $table) {
            $table->string('id')->unique();
            $table->string('absent_id');
            $table->string('product_id');
            $table->integer('item_taken');
            $table->integer('total_item_sold')->nullable();
            $table->string('sales_result')->nullable();

            $table->foreign('absent_id')->references('id')->on('absents')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onUpdate('cascade')->onDelete('cascade');

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
        Schema::dropIfExists('item_takens');
    }
};
