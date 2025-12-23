<?php

use App\Models\User;
use Notes\Models\Note;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseMissing;

test('can delete a note', function () {
    $user = User::factory()->create();
    $note = Note::factory()->create([
        'user_id' => $user->id,
        'title' => 'Test Note',
        'body' => ['text' => 'Test content'],
    ]);

    $response = actingAs($user)->deleteJson("/api/notes/{$note->id}");

    $response->assertStatus(204);

    assertDatabaseMissing('notes', [
        'id' => $note->id,
    ]);
});

test('returns 403 when trying to delete note of another user', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();
    $note = Note::factory()->create([
        'user_id' => $owner->id,
        'title' => 'Test Note',
        'body' => ['text' => 'Test content'],
    ]);

    $response = actingAs($otherUser)->deleteJson("/api/notes/{$note->id}");

    $response->assertStatus(403);
});

test('returns 404 when note does not exist', function () {
    $user = User::factory()->create();

    $response = actingAs($user)->deleteJson('/api/notes/0');

    $response->assertStatus(404);
});


