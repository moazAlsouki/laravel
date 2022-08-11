<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('photo');
            $table->date('expired');
            $table->string('type');
            $table->string('userEmail');
            $table->integer('quantity');
            $table->integer('views');
            $table->string('price');
            $table->string('price30');
            $table->string('price15');
            $table->string('priceOther');
            $table->foreign('userEmail')->references('email')->on('users');
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
        Schema::dropIfExists('products');
    }
}
