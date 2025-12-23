<?php

namespace Notes\Events;

readonly class NoteUpdated
{
    public function __construct(
        public int $noteId,
    ) {
    }
}

