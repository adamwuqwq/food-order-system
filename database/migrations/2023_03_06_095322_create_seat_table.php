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
        Schema::create('seats', function (Blueprint $table) {
            $table->bigIncrements('seat_id');
            $table->bigInteger('restaurant_id')->unsigned();
            $table->string('seat_name');
            $table->string('qr_code_token');
            $table->boolean('is_available')->default(true);
            $table->timestamps();
            $table->foreign('restaurant_id')->references('restaurant_id')->on('restaurants');
        });

        // TinyIntをBooleanに変換
        DB::statement('ALTER TABLE seats MODIFY is_available BOOLEAN');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seats');
    }
};
