<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Admins;
use App\Models\Restaurants;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AdminRestaurantRelationships>
 */
class AdminRestaurantRelationshipsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $adminId = Admins::inRandomOrder()->first()->admin_id;
        $restaurantId = Restaurants::inRandomOrder()->first()->restaurant_id;

        return [
            'admin_id' => $adminId,
            'restaurant_id' => $restaurantId,
            'admin_role' => Admins::find($adminId)->admin_role,
        ];
    }
}