<?php

namespace Notes\Exceptions;

use App\Exceptions\BaseException;

class NoteNotDeleted extends BaseException
{
    public function __construct(
        string $message = 'Note not deleted',
        int $code = 50_001,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getHttpStatusCode(): int
    {
        return 400;
    }
}
