<?php

namespace Notes\Commands\CreateNote;

use Illuminate\Contracts\Events\Dispatcher;
use Notes\Events\NoteCreated;
use Notes\Models\Note;
use Notes\Repositories\NoteRepository;

class Handler
{
    public function __construct(
        private NoteRepository $repository,
        private Dispatcher $eventDispatcher,
    ) {
    }

    public function handle(Command $command): Note
    {
        $note = $this->repository->createNote(
            $command->userId,
            $command->dto,
        );

        $this->eventDispatcher->dispatch(new NoteCreated($note->id));

        return $note;
    }
}