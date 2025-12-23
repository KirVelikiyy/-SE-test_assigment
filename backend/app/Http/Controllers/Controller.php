<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[
    OA\Info(
        version: "1.0.0",
        title: "Notes API",
        description: "API для управления заметками с аутентификацией через Laravel Passport"
    ),
    OA\Server(
        url: "/api",
        description: "API Server"
    ),
    OA\SecurityScheme(
        securityScheme: "passport",
        type: "http",
        scheme: "bearer",
        bearerFormat: "JWT",
        description: "Laravel Passport OAuth2 authentication"
    )
]
abstract class Controller
{
    //
}
