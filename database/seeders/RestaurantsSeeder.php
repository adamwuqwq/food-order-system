<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Restaurants;

class RestaurantsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Restaurants::factory()->count(4)->create();
    }
}
