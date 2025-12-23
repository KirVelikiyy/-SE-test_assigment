<?php

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Notes\Events\NoteDeleted;
use Notes\Models\Note;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseMissing;

test('can delete a note', function () {
    Event::fake();
    
    $user = User::factory()->create();
    $note = Note::factory()->create([
        'user_id' => $user->id,
        'title' => 'Test Note',
        'body' => ['text' => 'Test content'],
    ]);

    $noteId = $note->id;

    $response = actingAs($user)->deleteJson("/api/notes/{$note->id}");

    $response->assertStatus(204);

    assertDatabaseMissing('notes', [
        'id' => $noteId,
    ]);

    Event::assertDispatched(NoteDeleted::class, function ($event) use ($noteId) {
        return $event->note->id === $noteId;
    });
});

test('returns 403 when trying to delete note of another user', function () {
    Event::fake();
    
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();
    $note = Note::factory()->create([
        'user_id' => $owner->id,
        'title' => 'Test Note',
        'body' => ['text' => 'Test content'],
    ]);

    $response = actingAs($otherUser)->deleteJson("/api/notes/{$note->id}");

    $response->assertStatus(403);
    
    Event::assertNotDispatched(NoteDeleted::class);
});

test('returns 404 when note does not exist', function () {
    Event::fake();
    
    $user = User::factory()->create();

    $response = actingAs($user)->deleteJson('/api/notes/0');

    $response->assertStatus(404);
    
    Event::assertNotDispatched(NoteDeleted::class);
});

test('admin can delete note of another user', function () {
    Event::fake();
    
    $owner = User::factory()->create();
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $note = Note::factory()->create([
        'user_id' => $owner->id,
        'title' => 'Test Note',
        'body' => ['text' => 'Test content'],
    ]);

    $noteId = $note->id;

    $response = actingAs($admin)->deleteJson("/api/notes/{$note->id}");

    $response->assertStatus(204);

    assertDatabaseMissing('notes', [
        'id' => $noteId,
    ]);

    Event::assertDispatched(NoteDeleted::class, function ($event) use ($noteId) {
        return $event->note->id === $noteId;
    });
});


