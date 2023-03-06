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
        Schema::create('ordered_dishes', function (Blueprint $table) {
            $table->bigIncrements('ordered_dish_id');
            $table->bigInteger('order_id')->unsigned();
            $table->bigInteger('dish_id')->unsigned();
            $table->integer('quantity')->default(0);
            $table->boolean('is_delivered')->default(false);
            $table->timestamps();
            $table->foreign('order_id')->references('order_id')->on('orders');
            $table->foreign('dish_id')->references('dish_id')->on('dishes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordered_dishes');
    }
};
