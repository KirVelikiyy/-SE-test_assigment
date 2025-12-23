<?php

use App\Models\User;
use Illuminate\Support\Facades\Event;
use Laravel\Passport\Passport;
use Notes\Events\NoteCreated;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\postJson;

test('can create a note', function () {
    Event::fake();
    
    $user = User::factory()->create();

    $noteData = [
        'title' => 'Test Note',
        'body' => ['text' => 'This is a test note content'],
    ];

    Passport::actingAs($user);
    $response = postJson('/api/notes', $noteData);

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

    $noteId = $response->json('data.id');

    assertDatabaseHas('notes', [
        'title' => 'Test Note',
    ]);

    Event::assertDispatched(NoteCreated::class, function ($event) use ($noteId) {
        return $event->note->id === $noteId;
    });
});

test('returns 422 when title is missing', function () {
    $user = User::factory()->create();
    Passport::actingAs($user);
    
    $response = postJson('/api/notes', [
        'body' => ['text' => 'Some content'],
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['title']);
});

test('returns 422 when body is missing', function () {
    $user = User::factory()->create();
    Passport::actingAs($user);
    
    $response = postJson('/api/notes', [
        'title' => 'Test Note',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['body']);
});

test('returns 422 when title is too short', function () {
    $user = User::factory()->create();
    Passport::actingAs($user);
    
    $response = postJson('/api/notes', [
        'title' => 'A',
        'body' => ['text' => 'Some content'],
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['title']);
});

test('returns 422 when title is too long', function () {
    $user = User::factory()->create();
    Passport::actingAs($user);
    
    $response = postJson('/api/notes', [
        'title' => str_repeat('a', 256),
        'body' => ['text' => 'Some content'],
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['title']);
});

test('returns 422 when body is not an array', function () {
    $user = User::factory()->create();
    Passport::actingAs($user);
    
    $response = postJson('/api/notes', [
        'title' => 'Test Note',
        'body' => 'not an array',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['body']);
});

test('returns 422 when title is empty string', function () {
    $user = User::factory()->create();
    Passport::actingAs($user);
    
    $response = postJson('/api/notes', [
        'title' => '',
        'body' => ['text' => 'Some content'],
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['title']);
});

