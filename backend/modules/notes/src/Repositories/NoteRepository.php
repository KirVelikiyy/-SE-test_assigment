<?php

namespace Notes\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Notes\DTOs\NoteDTO;
use Notes\Models\Note;

class NoteRepository
{
    protected function query(): Builder
    {
        return (new Note())->newQuery();
    }

    public function createNote(int $userId, NoteDTO $dto): Note
    {
        return $this->query()->create([
            'user_id' => $userId,
            'title' => $dto->title,
            'body' => $dto->body,
        ]);
    }

    public function deleteNote(int $noteId): bool
    {
        return (bool)$this->query()
            ->where('id', $noteId)
            ->delete();
    }

    public function getNoteById(int $noteId): ?Note
    {
        return $this->query()
            ->where('id', $noteId)
            ->first();
    }

    public function updateNote(int $noteId, NoteDTO $dto): Note
    {
        $note = $this->query()->findOrFail($noteId);

        $note->update([
            'title' => $dto->title,
            'body' => $dto->body,
        ]);

        return $note->fresh();
    }

    public function getAllNotesByUserId(int $userId): \Illuminate\Database\Eloquent\Collection
    {
        return $this->query()
            ->where('user_id', $userId)
            ->get();
    }
}
