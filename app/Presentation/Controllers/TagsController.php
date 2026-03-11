<?php

namespace App\Presentation\Controllers;

use App\Application\Dto\StoreTagDto;
use App\Application\Dto\UpdateTagDto;
use App\Application\TagService;
use App\Domain\Exception\NotFoundException;
use App\Domain\Exception\StoreException;
use App\Presentation\Requests\StoreTagRequest;
use App\Presentation\Requests\UpdateTagRequest;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class TagsController extends BaseApiController
{
    public function __construct(
        public TagService $service
    )
    {}

    #[OA\Get(
        path: '/tags',
        summary: 'Список тегов',
        tags: ['Tags'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Список тегов',
                content: new OA\JsonContent(ref: '#/components/schemas/TagListResponse')
            ),
        ]
    )]
    public function index(): JsonResponse
    {
        $response = $this->service->getTagsList();
        return  new JsonResponse(
            $response
        );
    }

    #[OA\Get(
        path: '/tags/{id}',
        summary: 'Получить тег по ID',
        tags: ['Tags'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'ID тега',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Тег найден',
                content: new OA\JsonContent(ref: '#/components/schemas/TagSuccessResponse')
            ),
            new OA\Response(
                response: 404,
                description: 'Тег не найден',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
        ]
    )]
    public function show(int $tagId): JsonResponse
    {
        try{
            $response = $this->service->getTagById($tagId);
        } catch (NotFoundException $e) {
            return $this->failed($e->getMessage(), $e->getCode());
        }
        return new JsonResponse($response);
    }
    #[OA\Post(
        path: '/tags',
        summary: 'Создать тег',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/StoreTagRequest')
        ),
        tags: ['Tags'],
        responses: [
            new OA\Response(
                response: 201,
                description: 'Тег создан',
                content: new OA\JsonContent(ref: '#/components/schemas/TagSuccessResponse')
            ),
            new OA\Response(
                response: 422,
                description: 'Ошибка валидации',
                content: new OA\JsonContent(ref: '#/components/schemas/ValidationErrorResponse')
            ),
        ]
    )]
    public function store(StoreTagRequest $request): JsonResponse
    {
        $data = $request->validated();
        $storeTaskDto = new StoreTagDto(
            $data['description'],
        );
        try{
            $task = $this->service->storeTag($storeTaskDto);
        } catch (StoreException $e)
        {
            return $this->failed($e->getMessage(), $e->getCode());
        }
        return $this->success($task, 201);
    }

    #[OA\Patch(
        path: '/tags/{id}',
        summary: 'Обновить тег',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/UpdateTagRequest')
        ),
        tags: ['Tags'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'ID тега',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Тег обновлён',
                content: new OA\JsonContent(ref: '#/components/schemas/TagSuccessResponse')
            ),
            new OA\Response(
                response: 404,
                description: 'Тег не найден',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
            new OA\Response(
                response: 422,
                description: 'Ошибка валидации',
                content: new OA\JsonContent(ref: '#/components/schemas/ValidationErrorResponse')
            ),
        ]
    )]
    public function update(int $tagId, UpdateTagRequest $request): JsonResponse
    {
        $data = $request->validated();
        $storeTagDto = new UpdateTagDto(
            $data['description'],
        );
        try {
            $task = $this->service->updateTag($tagId, $storeTagDto);
        } catch (NotFoundException $e) {
            return $this->failed($e->getMessage(), $e->getCode());
        }

        return $this->success($task, 200);
    }

    #[OA\Delete(
        path: '/tags/{id}',
        summary: 'Удалить тег',
        tags: ['Tags'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'ID тега',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 204,
                description: 'Тег удалён'
            ),
            new OA\Response(
                response: 404,
                description: 'Тег не найден',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
        ]
    )]
    public function destroy(int $tagId): JsonResponse
    {
        try{
            $this->service->delete($tagId);
        } catch (NotFoundException $e) {
            return $this->failed($e->getMessage(), $e->getCode());
        }

        return $this->success(null, 204);
    }
}
