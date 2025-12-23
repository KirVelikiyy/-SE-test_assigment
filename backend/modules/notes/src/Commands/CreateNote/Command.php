<?php

namespace Notes\Commands\CreateNote;

use Notes\DTOs\NoteDTO;
use Notes\Models\Note;

readonly class Command
{
    public function __construct(
        public int $userId,
        public NoteDTO $dto,
    ) {
    }

    public function handle(Handler $handler): Note
    {
        return $handler->handle($this);
    }
}
