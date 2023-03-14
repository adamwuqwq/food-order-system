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
        Schema::create('admin_restaurant_relationship', function (Blueprint $table) {
            $table->bigIncrements('relationship_id');
            $table->bigInteger('admin_id')->unsigned();
            $table->bigInteger('restaurant_id')->unsigned();
            $table->enum('admin_role', ['system', 'owner', 'counter', 'kitchen']);
            $table->timestamps();
            $table->foreign('admin_id')->references('admin_id')->on('admin');
            $table->foreign('restaurant_id')->references('restaurant_id')->on('restaurant');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_restaurant_relationship');
    }
};
