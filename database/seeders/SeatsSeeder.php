<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Seats;

class SeatsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Seats::factory()->count(80)->create();
    }
}
