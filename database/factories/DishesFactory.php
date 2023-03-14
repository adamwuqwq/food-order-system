<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Restaurants;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Dishes>
 */
class DishesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'restaurant_id' => Restaurants::inRandomOrder()->first()->restaurant_id,
            'dish_name' => 'Test dish' . strval($this->faker->unique()->numberBetween(0, 1000)),
            'image_url' => $this->faker->imageUrl(640, 480, 'food'),
            'dish_category' => $this->faker->randomElement(['category A', 'category B', 'category C', 'category D']),
            'dish_description' => $this->faker->paragraph(3),
            'dish_price' => $this->faker->numberBetween(100, 1500),
            'available_num' => $this->faker->numberBetween(0, 50),
        ];
    }
}