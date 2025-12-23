<?php

namespace Notes\Http\Actions\Delete;

use Illuminate\Http\JsonResponse;

class Response extends JsonResponse
{
    public function __construct()
    {
        parent::__construct(null, 204);
    }
}

