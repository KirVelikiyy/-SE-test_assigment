<?php

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Notes\Events\NoteUpdated;
use Notes\Models\Note;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\putJson;

test('can update a note', function () {
    Event::fake();
    
    $user = User::factory()->create();
    $note = Note::factory()->create([
        'user_id' => $user->id,
        'title' => 'Original Title',
        'body' => ['text' => 'Original content'],
    ]);

    $updateData = [
        'title' => 'Updated Title',
        'body' => ['text' => 'Updated content'],
    ];

    $response = actingAs($user)->putJson("/api/notes/{$note->id}", $updateData);

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
                'title' => 'Updated Title',
                'body' => ['text' => 'Updated content'],
            ],
        ]);

    assertDatabaseHas('notes', [
        'id' => $note->id,
        'title' => 'Updated Title',
    ]);

    Event::assertDispatched(NoteUpdated::class, function ($event) use ($note) {
        return $event->noteId === $note->id;
    });
});

test('returns 403 when trying to update note of another user', function () {
    Event::fake();
    
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();
    $note = Note::factory()->create([
        'user_id' => $owner->id,
        'title' => 'Original Title',
        'body' => ['text' => 'Original content'],
    ]);

    $updateData = [
        'title' => 'Updated Title',
        'body' => ['text' => 'Updated content'],
    ];

    $response = actingAs($otherUser)->putJson("/api/notes/{$note->id}", $updateData);

    $response->assertStatus(403);
    
    Event::assertNotDispatched(NoteUpdated::class);
});

test('returns 404 when note does not exist', function () {
    Event::fake();
    
    $user = User::factory()->create();

    $updateData = [
        'title' => 'Updated Title',
        'body' => ['text' => 'Updated content'],
    ];

    $response = actingAs($user)->putJson('/api/notes/99999', $updateData);

    $response->assertStatus(404);
    
    Event::assertNotDispatched(NoteUpdated::class);
});

test('returns 422 when title is missing', function () {
    $user = User::factory()->create();
    $note = Note::factory()->create([
        'user_id' => $user->id,
    ]);

    $response = actingAs($user)->putJson("/api/notes/{$note->id}", [
        'body' => ['text' => 'Some content'],
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['title']);
});

test('returns 422 when body is missing', function () {
    $user = User::factory()->create();
    $note = Note::factory()->create([
        'user_id' => $user->id,
    ]);

    $response = actingAs($user)->putJson("/api/notes/{$note->id}", [
        'title' => 'Test Note',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['body']);
});

test('returns 422 when title is too short', function () {
    $user = User::factory()->create();
    $note = Note::factory()->create([
        'user_id' => $user->id,
    ]);

    $response = actingAs($user)->putJson("/api/notes/{$note->id}", [
        'title' => 'A',
        'body' => ['text' => 'Some content'],
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['title']);
});

test('returns 422 when title is too long', function () {
    $user = User::factory()->create();
    $note = Note::factory()->create([
        'user_id' => $user->id,
    ]);

    $response = actingAs($user)->putJson("/api/notes/{$note->id}", [
        'title' => str_repeat('a', 256),
        'body' => ['text' => 'Some content'],
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['title']);
});

test('returns 422 when body is not an array', function () {
    $user = User::factory()->create();
    $note = Note::factory()->create([
        'user_id' => $user->id,
    ]);

    $response = actingAs($user)->putJson("/api/notes/{$note->id}", [
        'title' => 'Test Note',
        'body' => 'not an array',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['body']);
});

test('admin can update note of another user', function () {
    Event::fake();
    
    $owner = User::factory()->create();
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $note = Note::factory()->create([
        'user_id' => $owner->id,
        'title' => 'Original Title',
        'body' => ['text' => 'Original content'],
    ]);

    $updateData = [
        'title' => 'Updated by Admin',
        'body' => ['text' => 'Updated content by admin'],
    ];

    $response = actingAs($admin)->putJson("/api/notes/{$note->id}", $updateData);

    $response->assertStatus(200)
        ->assertJson([
            'data' => [
                'id' => $note->id,
                'title' => 'Updated by Admin',
                'body' => ['text' => 'Updated content by admin'],
            ],
        ]);

    assertDatabaseHas('notes', [
        'id' => $note->id,
        'title' => 'Updated by Admin',
    ]);

    Event::assertDispatched(NoteUpdated::class, function ($event) use ($note) {
        return $event->noteId === $note->id;
    });
});

