<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Orders;
use App\Models\Restaurants;
use App\Models\Seats;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Orders>
 */
class OrdersFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $restaurantId = Restaurants::inRandomOrder()->first()->restaurant_id;
        $seatId = Restaurants::find($restaurantId)->seats()->where('is_available', true)->inRandomOrder()->first()->seat_id;

        return [
            'restaurant_id' => $restaurantId,
            'seat_id' => $seatId,
            'is_order_finished' => false,
            'is_paid' => false,
            'paid_at' => null,
            'created_at' => $this->faker->dateTimeBetween('-2 hours', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-2 hours', 'now'),
        ];
    }
}