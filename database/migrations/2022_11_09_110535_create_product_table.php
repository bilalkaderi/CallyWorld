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
        Schema::create('product', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('userId')->references('id')->on('users');
            $table->double('price');
            $table->string('photo')->nullable();
            $table->string('description');
            $table->integer('width');
            $table->integer('height');
            $table->integer('categoryId')->references('id')->on('categories');
            $table->integer('soldno')->default('0');
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
        Schema::dropIfExists('product');
    }
};
