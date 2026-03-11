<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;

#[OA\OpenApi(
    info: new OA\Info(
        version: '1.0.0',
        description: 'Простое CRUD API для задач и тегов',
        title: 'Todo REST API'
    ),
    servers: [
        new OA\Server(
            url: '/api/v1',
            description: 'API v1'
        )
    ]
)]
class OpenApi
{
}
