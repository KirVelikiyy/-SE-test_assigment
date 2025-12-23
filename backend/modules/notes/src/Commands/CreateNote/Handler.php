<?php

namespace Notes\Commands\CreateNote;

use Notes\Models\Note;
use Notes\Repositories\NoteRepository;

class Handler
{
    public function __construct(
        private NoteRepository $repository,
    ) {
    }

    public function handle(Command $command): Note
    {
        return $this->repository->createNote(
            $command->userId,
            $command->dto,
        );
    }
}