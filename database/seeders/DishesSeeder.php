<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dishes;

class DishesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Dishes::factory()->count(80)->create();
    }
}
