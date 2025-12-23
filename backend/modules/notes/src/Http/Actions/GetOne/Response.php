<?php

namespace Notes\Http\Actions\GetOne;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Notes\Models\Note;

/**
 * @property-read Note $resource
 */
class Response extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'user_id' => $this->resource->user_id,
            'title' => $this->resource->title,
            'body' => $this->resource->body,
            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at,
        ];
    }

    public function withResponse(Request $request, JsonResponse $response): void
    {
        $response->setStatusCode(200);
    }
}

