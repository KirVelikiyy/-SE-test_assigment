<?php

namespace Database\Seeders;

use App\Models\User;
use Notes\Models\Note;
use Illuminate\Database\Seeder;

class NoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users (including admin)
        $users = User::all();

        foreach ($users as $user) {
            // Create 5-10 notes for each user
            Note::factory()
                ->count(rand(5, 10))
                ->create([
                    'user_id' => $user->id,
                ]);
        }
    }
}

