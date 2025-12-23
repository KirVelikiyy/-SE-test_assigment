<?php

use App\Models\User;
use Notes\Models\Note;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;

test('can get a note', function () {
    $user = User::factory()->create();
    $note = Note::factory()->create([
        'user_id' => $user->id,
        'title' => 'Test Note',
        'body' => ['text' => 'Test content'],
    ]);

    $response = actingAs($user)->getJson("/api/notes/{$note->id}");

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'id',
                'user_id',
                'title',
                'body',
                'created_at',
                'updated_at',
            ],
        ])
        ->assertJson([
            'data' => [
                'id' => $note->id,
                'user_id' => $user->id,
                'title' => 'Test Note',
                'body' => ['text' => 'Test content'],
            ],
        ]);
});

test('returns 403 when trying to get note of another user', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();
    $note = Note::factory()->create([
        'user_id' => $owner->id,
        'title' => 'Test Note',
        'body' => ['text' => 'Test content'],
    ]);

    $response = actingAs($otherUser)->getJson("/api/notes/{$note->id}");

    $response->assertStatus(403);
});

test('returns 404 when note does not exist', function () {
    $user = User::factory()->create();

    $response = actingAs($user)->getJson('/api/notes/99999');

    $response->assertStatus(404);
});

