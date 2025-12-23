<?php

namespace Notes\Http\Actions\Delete;

use Illuminate\Bus\Dispatcher;
use Notes\Commands\DeleteNote\Command as DeleteNote;

class Action
{
    public function __construct(
        private Dispatcher $bus,
    ) {
    }

    public function __invoke(Request $request, int $noteId): Response
    {
        $user = $request->user();
         
        $command = new DeleteNote(
            userId: (int)$user?->id,
            noteId: $noteId,
        );

        $this->bus->dispatchSync($command);

        return new Response();
    }
}

