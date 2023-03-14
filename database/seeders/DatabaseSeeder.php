<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminsSeeder::class,
            RestaurantsSeeder::class,
            SeatsSeeder::class,
            AdminRestaurantRelationshipsSeeder::class,
            DishesSeeder::class,
            OrdersSeeder::class,
            OrderedDishesSeeder::class,
        ]);
    }
}
