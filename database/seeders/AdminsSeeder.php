<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admins;

class AdminsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // システム提供者側のsuperuser
        Admins::create([
            'admin_name' => 'SystemAdmin',
            'login_id' => 'system',
            'hashed_password' => Hash::make('!YuMeMi+'),
            'admin_role' => 'system',
        ]);

        Admins::factory()->count(20)->create();
    }
}