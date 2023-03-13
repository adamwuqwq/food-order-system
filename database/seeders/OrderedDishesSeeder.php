<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrderedDishes;

class OrderedDishesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OrderedDishes::factory()->count(50)->create();
    }
}
