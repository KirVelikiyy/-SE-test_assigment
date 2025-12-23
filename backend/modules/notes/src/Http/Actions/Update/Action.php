<?php

namespace Notes\Http\Actions\Update;

use Illuminate\Bus\Dispatcher;
use Notes\Commands\UpdateNote\Command as UpdateNote;
use Notes\DTOs\NoteDTO;

class Action
{
    public function __construct(
        private Dispatcher $bus,
    ) {
    }

    public function __invoke(Request $request, int $noteId): Response
    {
        $dto = NoteDTO::fromRequest($request);
        $user = $request->user();

        $command = new UpdateNote(
            userId: (int) $user?->id,
            noteId: $noteId,
            dto: $dto,
        );

        $note = $this->bus->dispatchSync($command);

        return new Response($note);
    }
}

