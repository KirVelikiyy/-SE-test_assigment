<?php

namespace App\Exceptions\Users;

use App\Exceptions\BaseException;

class UserNotFound extends BaseException
{
    public function __construct(
        string $message = 'User not found',
        int $code = 41_001,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getHttpStatusCode(): int
    {
        return 404;
    }
}

