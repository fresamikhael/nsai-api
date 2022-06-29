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
        Schema::create('absents', function (Blueprint $table) {
            $table->string('id')->unique();
            $table->string('user_id');
            $table->string('distributor_id');
            $table->time('clock_in');
            $table->time('clock_out')->nullable();
            $table->string('address');

            $table->string('item_photo')->nullable();
            $table->string('distributor_photo')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('distributor_id')->references('id')->on('distributors')->onUpdate('cascade')->onDelete('cascade');

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
        Schema::dropIfExists('absents');
    }
};
