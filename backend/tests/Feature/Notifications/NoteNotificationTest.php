<?php

use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Laravel\Passport\Passport;
use Notifications\Mail\AdminNoteCreatedMail;
use function Pest\Laravel\postJson;

test('sends admin notification when note is created', function () {
    Mail::fake();
    
    config(['mail.admin_recipient' => 'admin@example.com']);
    
    $user = User::factory()->create();

    $noteData = [
        'title' => 'Test Note',
        'body' => ['text' => 'This is a test note content'],
    ];

    Passport::actingAs($user);
    $response = postJson('/api/notes', $noteData);

    $response->assertStatus(201);

    Mail::assertSent(AdminNoteCreatedMail::class, function ($mail) {
        return $mail->hasTo('admin@example.com') && $mail->note->title === 'Test Note';
    });
});

test('sends notification to configured admin recipient', function () {
    Mail::fake();
    
    config(['mail.admin_recipient' => 'custom-admin@example.com']);
    
    $user = User::factory()->create();

    $noteData = [
        'title' => 'Test Note',
        'body' => ['text' => 'This is a test note content'],
    ];

    Passport::actingAs($user);
    postJson('/api/notes', $noteData);

    Mail::assertSent(AdminNoteCreatedMail::class, function ($mail) {
        return $mail->hasTo('custom-admin@example.com');
    });
});

