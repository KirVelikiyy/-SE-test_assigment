<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user with fixed data
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => UserRole::Admin,
            ]
        );

        // Create 5-10 regular users
        User::factory()
            ->count(rand(5, 10))
            ->create([
                'role' => UserRole::User,
            ]);
    }
}

