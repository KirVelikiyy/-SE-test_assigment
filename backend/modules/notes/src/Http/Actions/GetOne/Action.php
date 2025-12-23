<?php

namespace Notes\Http\Actions\GetOne;

use Illuminate\Bus\Dispatcher;
use Notes\Commands\GetNote\Command as GetNote;

class Action
{
    public function __construct(
        private Dispatcher $bus,
    ) {
    }

    public function __invoke(Request $request, int $noteId): Response
    {
        $user = $request->user();

        $command = new GetNote(
            userId: (int) $user?->id,
            noteId: $noteId,
        );

        $note = $this->bus->dispatchSync($command);

        return new Response($note);
    }
}

