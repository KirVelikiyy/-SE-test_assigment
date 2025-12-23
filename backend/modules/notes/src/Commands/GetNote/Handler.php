<?php

namespace Notes\Commands\GetNote;

use App\Repositories\UserRepository;
use Notes\Exceptions\NoteNotFound;
use Notes\Exceptions\NotePermissionDenied;
use Notes\Models\Note;
use Notes\Repositories\NoteRepository;

readonly class Handler
{
    public function __construct(
        private NoteRepository $repository,
        private UserRepository $userRepository,
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

        return $note;
    }
}

