<?php

use App\Models\User;
use Laravel\Passport\Passport;
use Notes\Models\Note;
use function Pest\Laravel\getJson;

test('can get all notes for authenticated user', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $userNotes = Note::factory()->count(3)->create([
        'user_id' => $user->id,
    ]);

    $otherUserNotes = Note::factory()->count(2)->create([
        'user_id' => $otherUser->id,
    ]);

    Passport::actingAs($user);
    $response = getJson('/api/notes');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'user_id',
                    'title',
                    'body',
                    'created_at',
                    'updated_at',
                ],
            ],
        ]);

    $responseData = $response->json('data');
    expect($responseData)->toHaveCount(3);

    $noteIds = collect($responseData)->pluck('id')->toArray();
    $userNoteIds = $userNotes->pluck('id')->toArray();

    expect($noteIds)->toEqual(array_values($userNoteIds));
});

test('returns empty array when user has no notes', function () {
    $user = User::factory()->create();

    Passport::actingAs($user);
    $response = getJson('/api/notes');

    $response->assertStatus(200)
        ->assertJson([
            'data' => [],
        ]);
});

test('returns only notes belonging to authenticated user', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $userNote = Note::factory()->create([
        'user_id' => $user->id,
        'title' => 'My Note',
    ]);

    $otherUserNote = Note::factory()->create([
        'user_id' => $otherUser->id,
        'title' => 'Other User Note',
    ]);

    Passport::actingAs($user);
    $response = getJson('/api/notes');

    $response->assertStatus(200);

    $responseData = $response->json('data');
    expect($responseData)->toHaveCount(1);
    expect($responseData[0]['id'])->toBe($userNote->id);
    expect($responseData[0]['title'])->toBe('My Note');
});

