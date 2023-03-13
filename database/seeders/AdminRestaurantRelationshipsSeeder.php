<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdminRestaurantRelationships;

class AdminRestaurantRelationshipsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AdminRestaurantRelationships::factory()->count(20)->create();
    }
}
