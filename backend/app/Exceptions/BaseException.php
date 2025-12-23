<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

abstract class BaseException extends Exception
{
    public function __construct(string $message, int $code, ?Throwable $previous = null)
    {
        // Проверка диапазона кодов
        if ($code < 10_000 || $code > 99_999) {
            throw new \InvalidArgumentException("Код ошибки должен быть в диапазоне от 10000 до 99999.");
        }

        parent::__construct($message, $code, $previous);
    }

    /**
     * Преобразует внутренний код в HTTP-статус.
     */
    abstract public function getHttpStatusCode(): int;


    public function render(Request $request): JsonResponse
    {
        return response()->json([
            'error' => [
                'message' => $this->getMessage(),
                'code'    => $this->getCode(),
            ]
        ], $this->getHttpStatusCode());
    }
}
