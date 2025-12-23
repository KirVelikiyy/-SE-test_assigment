<?php

namespace Notes\Events;

use Notes\Models\Note;

readonly class NoteCreated
{
    public function __construct(
        public Note $note,
    ) {
    }
}

