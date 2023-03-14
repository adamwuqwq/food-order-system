<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Restaurants;
use App\Models\Seats;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Seats>
 */
class SeatsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $restaurantId = Restaurants::inRandomOrder()->first()->restaurant_id;

        return [
            'restaurant_id' => $restaurantId,
            'seat_name' => 'Seat ' . intval($this->faker->unique()->numberBetween(1, 100)),
            'qr_code_token' => Str::uuid(),
            'is_available' => true,
        ];
    }
}