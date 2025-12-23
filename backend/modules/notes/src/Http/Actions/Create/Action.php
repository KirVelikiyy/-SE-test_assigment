<?php

namespace Notes\Http\Actions\Create;

use Illuminate\Bus\Dispatcher;
use Notes\Commands\CreateNote\Command as CreateNote;
use Notes\DTOs\NoteDTO;

class Action
{
    public function __construct(
        private Dispatcher $bus,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $dto = NoteDTO::fromRequest($request);

        $command = new CreateNote(
            userId: (int) $request->user()?->getAuthIdentifier(),
            dto: $dto,
        );

        $note = $this->bus->dispatchSync($command);

        return new Response($note);
    }
}
