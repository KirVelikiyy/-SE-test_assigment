<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Notes\Http\Actions\Create\Action as CreateNoteAction;
use Notes\Http\Actions\Delete\Action as DeleteNoteAction;
use Notes\Http\Actions\GetAll\Action as GetAllNotesAction;
use Notes\Http\Actions\GetOne\Action as GetNoteAction;
use Notes\Http\Actions\Update\Action as UpdateNoteAction;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Notes", description: "API для управления заметками")]
class NoteController extends Controller
{
    #[
        OA\Get(
            path: "/notes",
            summary: "Получить все заметки текущего пользователя",
            tags: ["Notes"],
            security: [["passport" => []]],
            responses: [
                new OA\Response(
                    response: 200,
                    description: "Список заметок",
                    content: new OA\JsonContent(
                        properties: [
                            new OA\Property(
                                property: "data",
                                type: "array",
                                items: new OA\Items(
                                    properties: [
                                        new OA\Property(property: "id", type: "integer", example: 1),
                                        new OA\Property(property: "user_id", type: "integer", example: 1),
                                        new OA\Property(property: "title", type: "string", example: "My Note"),
                                        new OA\Property(
                                            property: "body",
                                            type: "object",
                                            example: ["text" => "Note content"]
                                        ),
                                        new OA\Property(property: "created_at", type: "string", format: "date-time"),
                                        new OA\Property(property: "updated_at", type: "string", format: "date-time"),
                                    ],
                                    type: "object"
                                )
                            ),
                        ]
                    )
                ),
                new OA\Response(response: 401, description: "Unauthorized"),
            ]
        )
    ]
    public function index(Request $request, GetAllNotesAction $action)
    {
        return $action($request);
    }

    #[
        OA\Post(
            path: "/notes",
            summary: "Создать новую заметку",
            tags: ["Notes"],
            security: [["passport" => []]],
            requestBody: new OA\RequestBody(
                required: true,
                content: new OA\JsonContent(
                    required: ["title", "body"],
                    properties: [
                        new OA\Property(property: "title", type: "string", minLength: 2, maxLength: 255, example: "My New Note"),
                        new OA\Property(
                            property: "body",
                            type: "object",
                            example: ["text" => "This is the note content"]
                        ),
                    ]
                )
            ),
            responses: [
                new OA\Response(
                    response: 201,
                    description: "Заметка успешно создана",
                    content: new OA\JsonContent(
                        properties: [
                            new OA\Property(
                                property: "data",
                                properties: [
                                    new OA\Property(property: "id", type: "integer", example: 1),
                                    new OA\Property(property: "user_id", type: "integer", example: 1),
                                    new OA\Property(property: "title", type: "string", example: "My New Note"),
                                    new OA\Property(
                                        property: "body",
                                        type: "object",
                                        example: ["text" => "This is the note content"]
                                    ),
                                    new OA\Property(property: "created_at", type: "string", format: "date-time"),
                                    new OA\Property(property: "updated_at", type: "string", format: "date-time"),
                                ],
                                type: "object"
                            ),
                        ]
                    )
                ),
                new OA\Response(response: 401, description: "Unauthorized"),
                new OA\Response(
                    response: 422,
                    description: "Validation error",
                    content: new OA\JsonContent(
                        properties: [
                            new OA\Property(
                                property: "message",
                                type: "string",
                                example: "The title field is required."
                            ),
                            new OA\Property(
                                property: "errors",
                                type: "object",
                                example: ["title" => ["The title field is required."]]
                            ),
                        ]
                    )
                ),
            ]
        )
    ]
    public function store(Request $request, CreateNoteAction $action)
    {
        return $action($request);
    }

    #[
        OA\Get(
            path: "/notes/{id}",
            summary: "Получить заметку по ID",
            tags: ["Notes"],
            security: [["passport" => []]],
            parameters: [
                new OA\Parameter(
                    name: "id",
                    in: "path",
                    required: true,
                    description: "ID заметки",
                    schema: new OA\Schema(type: "integer", example: 1)
                ),
            ],
            responses: [
                new OA\Response(
                    response: 200,
                    description: "Детали заметки",
                    content: new OA\JsonContent(
                        properties: [
                            new OA\Property(
                                property: "data",
                                properties: [
                                    new OA\Property(property: "id", type: "integer", example: 1),
                                    new OA\Property(property: "user_id", type: "integer", example: 1),
                                    new OA\Property(property: "title", type: "string", example: "My Note"),
                                    new OA\Property(
                                        property: "body",
                                        type: "object",
                                        example: ["text" => "Note content"]
                                    ),
                                    new OA\Property(property: "created_at", type: "string", format: "date-time"),
                                    new OA\Property(property: "updated_at", type: "string", format: "date-time"),
                                ],
                                type: "object"
                            ),
                        ]
                    )
                ),
                new OA\Response(response: 401, description: "Unauthorized"),
                new OA\Response(response: 403, description: "Forbidden - нет доступа к этой заметке"),
                new OA\Response(response: 404, description: "Not Found - заметка не найдена"),
            ]
        )
    ]
    public function show(Request $request, int $id, GetNoteAction $action)
    {
        return $action($request, $id);
    }

    #[
        OA\Put(
            path: "/notes/{id}",
            summary: "Обновить заметку",
            tags: ["Notes"],
            security: [["passport" => []]],
            parameters: [
                new OA\Parameter(
                    name: "id",
                    in: "path",
                    required: true,
                    description: "ID заметки",
                    schema: new OA\Schema(type: "integer", example: 1)
                ),
            ],
            requestBody: new OA\RequestBody(
                required: true,
                content: new OA\JsonContent(
                    required: ["title", "body"],
                    properties: [
                        new OA\Property(property: "title", type: "string", minLength: 2, maxLength: 255, example: "Updated Note"),
                        new OA\Property(
                            property: "body",
                            type: "object",
                            example: ["text" => "Updated note content"]
                        ),
                    ]
                )
            ),
            responses: [
                new OA\Response(
                    response: 200,
                    description: "Заметка успешно обновлена",
                    content: new OA\JsonContent(
                        properties: [
                            new OA\Property(
                                property: "data",
                                properties: [
                                    new OA\Property(property: "id", type: "integer", example: 1),
                                    new OA\Property(property: "user_id", type: "integer", example: 1),
                                    new OA\Property(property: "title", type: "string", example: "Updated Note"),
                                    new OA\Property(
                                        property: "body",
                                        type: "object",
                                        example: ["text" => "Updated note content"]
                                    ),
                                    new OA\Property(property: "created_at", type: "string", format: "date-time"),
                                    new OA\Property(property: "updated_at", type: "string", format: "date-time"),
                                ],
                                type: "object"
                            ),
                        ]
                    )
                ),
                new OA\Response(response: 401, description: "Unauthorized"),
                new OA\Response(response: 403, description: "Forbidden - нет доступа к этой заметке"),
                new OA\Response(response: 404, description: "Not Found - заметка не найдена"),
                new OA\Response(
                    response: 422,
                    description: "Validation error",
                    content: new OA\JsonContent(
                        properties: [
                            new OA\Property(
                                property: "message",
                                type: "string",
                                example: "The title field is required."
                            ),
                            new OA\Property(
                                property: "errors",
                                type: "object",
                                example: ["title" => ["The title field is required."]]
                            ),
                        ]
                    )
                ),
            ]
        )
    ]
    public function update(Request $request, int $id, UpdateNoteAction $action)
    {
        return $action($request, $id);
    }

    #[
        OA\Delete(
            path: "/notes/{id}",
            summary: "Удалить заметку",
            tags: ["Notes"],
            security: [["passport" => []]],
            parameters: [
                new OA\Parameter(
                    name: "id",
                    in: "path",
                    required: true,
                    description: "ID заметки",
                    schema: new OA\Schema(type: "integer", example: 1)
                ),
            ],
            responses: [
                new OA\Response(response: 204, description: "Заметка успешно удалена"),
                new OA\Response(response: 401, description: "Unauthorized"),
                new OA\Response(response: 403, description: "Forbidden - нет доступа к этой заметке"),
                new OA\Response(response: 404, description: "Not Found - заметка не найдена"),
            ]
        )
    ]
    public function destroy(Request $request, int $id, DeleteNoteAction $action)
    {
        return $action($request, $id);
    }
}

