<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Notes\Models\Note;

class NoteFactory extends Factory
{
    protected $model = Note::class;

    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'title' => fake()->sentence(),
            'body' => ['text' => fake()->paragraph()],
        ];
    }
}
