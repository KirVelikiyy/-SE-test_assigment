<?php

namespace Notes\DTOs;

use Illuminate\Http\Request;

readonly class NoteDTO
{
    public function __construct(
        public string $title,
        public array $body,
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            $request->string('title')->toString(),
            (array) $request->input('body', []),
        );
    }
}
