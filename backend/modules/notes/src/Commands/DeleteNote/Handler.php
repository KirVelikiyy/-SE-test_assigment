<?php

namespace Notes\Commands\DeleteNote;

use Notes\Exceptions\NoteNotDeleted;
use Notes\Exceptions\NoteNotFound;
use Notes\Exceptions\NotePermissionDenied;
use Notes\Repositories\NoteRepository;

readonly class Handler
{
    public function __construct(
        private NoteRepository $repository
    ) {
    }

    public function handle(Command $command): void
    {
        $note = $this->repository->getNoteById($command->noteId);
        if (!$note) {
            throw new NoteNotFound();
        }

        if ($note->user_id != $command->userId) {
            throw new NotePermissionDenied();
        }
        
        $deleted = $this->repository->deleteNote($command->noteId);
        if (!$deleted) {
            throw new NoteNotDeleted();
        }
    }
}

