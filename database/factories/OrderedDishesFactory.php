<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Restaurants;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderedDishes>
 */
class OrderedDishesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $restaurantId = Restaurants::inRandomOrder()->first()->restaurant_id;
        $restaurant = Restaurants::find($restaurantId);
        $dishId = $restaurant->dishes()->inRandomOrder()->first()->dish_id;
        $orderId = $restaurant->orders()->inRandomOrder()->first()->order_id;
        $createdAt = $restaurant->orders()->find($orderId)->created_at;

        return [
            'restaurant_id' => $restaurantId,
            'order_id' => $orderId,
            'dish_id' => $dishId,
            'quantity' => $this->faker->numberBetween(1, 5),
            'is_delivered' => false,
            'is_canceled' => $this->faker->boolean(10),
            'created_at' => $createdAt,
        ];
    }
}
