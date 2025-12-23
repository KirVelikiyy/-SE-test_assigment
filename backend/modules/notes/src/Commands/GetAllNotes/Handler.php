<?php

namespace Notes\Commands\GetAllNotes;

use Illuminate\Database\Eloquent\Collection;
use Notes\Repositories\NoteRepository;

readonly class Handler
{
    public function __construct(
        private NoteRepository $repository,
    ) {
    }

    public function handle(Command $command): Collection
    {
        return $this->repository->getAllNotesByUserId($command->userId);
    }
}

