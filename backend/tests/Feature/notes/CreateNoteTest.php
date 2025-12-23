<?php

use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\postJson;

test('can create a note', function () {
    $user = User::factory()->create();

    $noteData = [
        'title' => 'Test Note',
        'body' => ['text' => 'This is a test note content'],
    ];

    $response = actingAs($user)->postJson('/api/notes', $noteData);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'data' => [
                'id',
                'user_id',
                'title',
                'body',
                'created_at',
                'updated_at',
            ],
        ]);

    assertDatabaseHas('notes', [
        'title' => 'Test Note',
    ]);
});

test('returns 422 when title is missing', function () {
    $response = postJson('/api/notes', [
        'body' => ['text' => 'Some content'],
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['title']);
});

test('returns 422 when body is missing', function () {
    $response = postJson('/api/notes', [
        'title' => 'Test Note',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['body']);
});

test('returns 422 when title is too short', function () {
    $response = postJson('/api/notes', [
        'title' => 'A',
        'body' => ['text' => 'Some content'],
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['title']);
});

test('returns 422 when title is too long', function () {
    $response = postJson('/api/notes', [
        'title' => str_repeat('a', 256),
        'body' => ['text' => 'Some content'],
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['title']);
});

test('returns 422 when body is not an array', function () {
    $response = postJson('/api/notes', [
        'title' => 'Test Note',
        'body' => 'not an array',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['body']);
});

test('returns 422 when title is empty string', function () {
    $response = postJson('/api/notes', [
        'title' => '',
        'body' => ['text' => 'Some content'],
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['title']);
});

