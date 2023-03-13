<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Admins>
 */
class AdminsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'admin_name' => $this->faker->name(),
            'login_id' => $this->faker->unique()->userName(),
            'hashed_password' => Hash::make('!YuMeMi+'),
            'admin_role' => $this->faker->randomElement(['owner', 'counter', 'kitchen']),
        ];
    }
}
