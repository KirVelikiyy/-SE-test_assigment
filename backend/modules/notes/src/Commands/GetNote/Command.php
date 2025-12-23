<?php

namespace Notes\Commands\GetNote;

use Notes\Models\Note;

readonly class Command
{
    public function __construct(
        public int $userId,
        public int $noteId,
    ) {
    }

    public function handle(Handler $handler): Note
    {
        return $handler->handle($this);
    }
}

