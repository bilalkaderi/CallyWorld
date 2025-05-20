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
        Schema::create('cart', function (Blueprint $table) {
          $table->id();
          $table->unsignedBigInteger('clientId');
          $table->json('items')->nullable();
          $table->json('quantity')->nullable();
          $table->string('status')->default('Checking');
          $table->double('total_amount');
          $table->DateTime('expecting_delivery_date');
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
        Schema::dropIfExists('cart');
    }
};
