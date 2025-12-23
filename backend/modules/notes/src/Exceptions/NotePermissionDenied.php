<?php

namespace Notes\Exceptions;

use App\Exceptions\BaseException;

class NotePermissionDenied extends BaseException
{
    public function __construct(
        string $message = 'You can not manipulate with this note',
        int $code = 43_001,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getHttpStatusCode(): int
    {
        return 403;
    }
}
