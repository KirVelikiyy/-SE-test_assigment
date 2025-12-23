<?php

namespace Notes\Commands\GetNote;

use Notes\Exceptions\NoteNotFound;
use Notes\Exceptions\NotePermissionDenied;
use Notes\Models\Note;
use Notes\Repositories\NoteRepository;

readonly class Handler
{
    public function __construct(
        private NoteRepository $repository,
    ) {
    }

    public function handle(Command $command): Note
    {
        $note = $this->repository->getNoteById($command->noteId);
        if (! $note) {
            throw new NoteNotFound();
        }

        if ($note->user_id != $command->userId) {
            throw new NotePermissionDenied();
        }

        return $note;
    }
}

