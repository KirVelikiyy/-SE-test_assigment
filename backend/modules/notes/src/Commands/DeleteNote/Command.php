<?php

namespace Notes\Commands\DeleteNote;

readonly class Command
{
    public function __construct(
        public int $userId,
        public int $noteId,
    ) {
    }

    public function handle(Handler $handler): void
    {
        $handler->handle($this);
    }
}
