<?php

namespace Notes\Http\Actions\Create;

use Illuminate\Foundation\Http\FormRequest;

class Request extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:2', 'max:255'],
            'body' => ['required', 'array'],
        ];
    }
}
