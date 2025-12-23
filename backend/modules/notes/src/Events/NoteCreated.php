<?php

namespace Notes\Events;

readonly class NoteCreated
{
    public function __construct(
        public int $noteId,
    ) {
    }
}

