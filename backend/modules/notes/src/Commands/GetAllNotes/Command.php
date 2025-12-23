<?php

namespace Notes\Commands\GetAllNotes;

use Illuminate\Database\Eloquent\Collection;

readonly class Command
{
    public function __construct(
        public int $userId,
    ) {
    }

    public function handle(Handler $handler): Collection
    {
        return $handler->handle($this);
    }
}

