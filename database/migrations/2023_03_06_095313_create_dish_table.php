<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dish', function (Blueprint $table) {
            $table->bigIncrements('dish_id');
            $table->bigInteger('restaurant_id')->unsigned();
            $table->string('dish_name');
            $table->string('image_url')->nullable();
            $table->string('dish_category')->default('unspecified');
            $table->text('dish_description')->nullable();
            $table->integer('dish_price')->default(0);
            $table->integer('available_num')->default(0);
            $table->timestamps();
            $table->foreign('restaurant_id')->references('restaurant_id')->on('restaurant');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dish');
    }
};
