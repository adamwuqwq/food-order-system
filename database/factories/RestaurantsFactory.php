<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Restaurants>
 */
class RestaurantsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'restaurant_name' => $this->faker->unique()->company(),
            'restaurant_address' => $this->faker->address(),
            'restaurant_image_url' => $this->faker->imageUrl(640, 480, 'restaurant'),
        ];
    }
}
