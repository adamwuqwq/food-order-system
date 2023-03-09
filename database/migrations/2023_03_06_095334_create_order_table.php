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
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('order_id');
            $table->bigInteger('restaurant_id')->unsigned();
            $table->bigInteger('seat_id')->unsigned();
            $table->boolean('is_order_finished')->default(false);
            $table->boolean('is_paid')->default(false);
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            $table->foreign('restaurant_id')->references('restaurant_id')->on('restaurants');
            $table->foreign('seat_id')->references('seat_id')->on('seats')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
