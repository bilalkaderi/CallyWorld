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
        Schema::create('orders', function (Blueprint $table) {
          $table->id();
          $table->string('name');
          $table->integer('productId')->references('id')->on('product');
          $table->integer('clientId')->references('id')->on('client');
          $table->integer('userId')->references('id')->on('user');
          $table->integer('quantity');
          $table->double('total_price');
          $table->string('status')->default('pending');
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
        Schema::dropIfExists('orders');
    }
};
