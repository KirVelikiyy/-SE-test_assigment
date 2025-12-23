<?php

namespace Notes\Exceptions;

use App\Exceptions\BaseException;

class NoteNotFound extends BaseException
{
    public function __construct(
        string $message = 'Note not found',
        int $code = 40_001,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getHttpStatusCode(): int
    {
        return 404;
    }
}
