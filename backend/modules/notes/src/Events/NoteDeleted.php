<?php

namespace Notes\Events;

use Notes\Models\Note;

readonly class NoteDeleted
{
    public function __construct(
        public Note $note,
    ) {
    }
}

