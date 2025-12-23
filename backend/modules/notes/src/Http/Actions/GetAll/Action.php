<?php

namespace Notes\Http\Actions\GetAll;

use Illuminate\Bus\Dispatcher;
use Notes\Commands\GetAllNotes\Command as GetAllNotes;

class Action
{
    public function __construct(
        private Dispatcher $bus,
    ) {
    }

    public function __invoke(Request $request): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $user = $request->user();

        $command = new GetAllNotes(
            userId: (int) $user?->id,
        );

        $notes = $this->bus->dispatchSync($command);

        return Response::collection($notes);
    }
}

