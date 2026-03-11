<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Task',
    required: ['id', 'title', 'description', 'status', 'priority', 'due_at'],
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'title', type: 'string', example: 'Buy milk'),
        new OA\Property(property: 'description', type: 'string', example: 'Need to buy milk on the way home'),
        new OA\Property(property: 'status', description: 'Enum TaskStatusEnum', type: 'string', example: 'new, in_progress, done'),
        new OA\Property(property: 'priority', description: 'Enum TaskPriorityEnum', type: 'string', example: 'low, medium, high'),
        new OA\Property(property: 'due_at', description: 'string', type: 'string', example: '2024-03-09 16:00:00'),
        new OA\Property(
            property: 'tags',
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/Tag')
        ),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'Tag',
    required: ['id', 'description'],
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'description', type: 'string', example: 'Personal'),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'StoreTaskRequest',
    required: ['title', 'description', 'status', 'priority', 'due_at'],
    properties: [
        new OA\Property(property: 'title', type: 'string', maxLength: 255),
        new OA\Property(property: 'description', type: 'string'),
        new OA\Property(property: 'status', description: 'TaskStatusEnum value', type: 'string'),
        new OA\Property(property: 'priority', description: 'TaskPriorityEnum value', type: 'string'),
        new OA\Property(property: 'due_at', description: 'Дата/время, парсится в timestamp', type: 'string'),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'UpdateTaskRequest',
    properties: [
        new OA\Property(property: 'title', type: 'string', maxLength: 255),
        new OA\Property(property: 'description', type: 'string', nullable: true),
        new OA\Property(property: 'status', description: 'TaskStatusEnum value', type: 'string'),
        new OA\Property(property: 'priority', description: 'TaskPriorityEnum value', type: 'string'),
        new OA\Property(property: 'due_at', type: 'string', format: 'date-time', nullable: true),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'SyncTagsRequest',
    required: ['tagsIdList'],
    properties: [
        new OA\Property(
            property: 'tagsIdList',
            type: 'array',
            items: new OA\Items(type: 'integer'),
            maxItems: 255
        ),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'StoreTagRequest',
    required: ['description'],
    properties: [
        new OA\Property(property: 'description', type: 'string'),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'UpdateTagRequest',
    required: ['description'],
    properties: [
        new OA\Property(property: 'description', type: 'string'),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'BaseSuccessEnvelope',
    properties: [
        new OA\Property(property: 'success', type: 'boolean', example: true),
        new OA\Property(property: 'data'),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'BaseErrorEnvelope',
    properties: [
        new OA\Property(property: 'success', type: 'boolean', example: false),
        new OA\Property(property: 'data'),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'TaskSuccessResponse',
    allOf: [
        new OA\Schema(ref: '#/components/schemas/BaseSuccessEnvelope'),
        new OA\Schema(
            properties: [
                new OA\Property(property: 'data', ref: '#/components/schemas/Task'),
            ]
        ),
    ]
)]
#[OA\Schema(
    schema: 'TagSuccessResponse',
    allOf: [
        new OA\Schema(ref: '#/components/schemas/BaseSuccessEnvelope'),
        new OA\Schema(
            properties: [
                new OA\Property(property: 'data', ref: '#/components/schemas/Tag'),
            ]
        ),
    ]
)]
#[OA\Schema(
    schema: 'TagListResponse',
    allOf: [
        new OA\Schema(ref: '#/components/schemas/BaseSuccessEnvelope'),
        new OA\Schema(
            properties: [
                new OA\Property(
                    property: 'data',
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/Tag')
                ),
            ]
        ),
    ]
)]
#[OA\Schema(
    schema: 'ErrorResponse',
    allOf: [
        new OA\Schema(ref: '#/components/schemas/BaseErrorEnvelope'),
        new OA\Schema(
            properties: [
                new OA\Property(
                    property: 'data',
                    type: 'string',
                    example: 'Задача с айди 1 не найдена',
                    nullable: true
                ),
            ]
        ),
    ]
)]
#[OA\Schema(
    schema: 'ValidationErrorResponse',
    properties: [
        new OA\Property(
            property: 'message',
            type: 'string',
            example: 'Validation failed. (title field is required)'
        ),
    ],
    type: 'object'
)]
class Schemas
{
}

