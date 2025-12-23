<?php

namespace Notes\Commands\UpdateNote;

use App\Repositories\UserRepository;
use Illuminate\Contracts\Events\Dispatcher;
use Notes\Events\NoteUpdated;
use Notes\Exceptions\NoteNotFound;
use Notes\Exceptions\NotePermissionDenied;
use Notes\Models\Note;
use Notes\Repositories\NoteRepository;

readonly class Handler
{
    public function __construct(
        private NoteRepository $repository,
        private UserRepository $userRepository,
        private Dispatcher $eventDispatcher,
    ) {
    }

    public function handle(Command $command): Note
    {
        $note = $this->repository->getNoteById($command->noteId);
        if (! $note) {
            throw new NoteNotFound();
        }

        $user = $this->userRepository->getUserById($command->userId);

        if ($note->user_id != $command->userId && !$user->isAdmin()) {
            throw new NotePermissionDenied();
        }

        $updatedNote = $this->repository->updateNote($command->noteId, $command->dto);

        $this->eventDispatcher->dispatch(new NoteUpdated($updatedNote->id));

        return $updatedNote;
    }
}

