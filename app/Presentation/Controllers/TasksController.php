<?php

namespace App\Presentation\Controllers;

use App\Application\Dto\StoreTaskDto;
use App\Application\Dto\SyncTagDto;
use App\Application\Dto\UpdateTaskDto;
use App\Application\TaskService;
use App\Domain\Exception\NotFoundException;
use App\Domain\Exception\StoreException;
use App\Presentation\Requests\StoreTaskRequest;
use App\Presentation\Requests\SyncTagsRequest;
use App\Presentation\Requests\UpdateTaskRequest;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;


class TasksController extends BaseApiController
{
    public function __construct(
        public TaskService $service
    )
    {}
    #[OA\Get(
        path: '/tasks',
        summary: 'Список задач',
        tags: ['Tasks'],
        parameters: [
            new OA\Parameter(
                name: 'page',
                description: 'Номер страницы',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'integer', minimum: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Список задач (пагинация Laravel)',
                content: new OA\JsonContent(ref: '#/components/schemas/BaseSuccessEnvelope')
            ),
        ]
    )]
    public function index(): JsonResponse
    {
        $response = $this->service->getTasksList();
        return new JsonResponse(
            $response
        );
    }
    #[OA\Get(
        path: '/tasks/{id}',
        summary: 'Получить задачу по ID',
        tags: ['Tasks'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'ID задачи',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Задача найдена',
                content: new OA\JsonContent(ref: '#/components/schemas/TaskSuccessResponse')
            ),
            new OA\Response(
                response: 404,
                description: 'Задача не найдена',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
        ]
    )]
    public function show(int $taskId): JsonResponse
    {
        try{
            $response = $this->service->getTaskById($taskId);
        } catch (NotFoundException $e) {
            return $this->failed($e->getMessage(), $e->getCode());
        }
        return new JsonResponse($response);
    }
    #[OA\Post(
        path: '/tasks',
        summary: 'Создать задачу',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/StoreTaskRequest')
        ),
        tags: ['Tasks'],
        responses: [
            new OA\Response(
                response: 201,
                description: 'Задача создана',
                content: new OA\JsonContent(ref: '#/components/schemas/TaskSuccessResponse')
            ),
            new OA\Response(
                response: 422,
                description: 'Ошибка валидации',
                content: new OA\JsonContent(ref: '#/components/schemas/ValidationErrorResponse')
            ),
        ]
    )]
    public function store(StoreTaskRequest $request): JsonResponse
    {
        $data = $request->validated();
        $storeTaskDto = new StoreTaskDto(
            $data['title'],
            $data['description'],
            $data['status'],
            $data['priority'],
            $data['due_at']
        );
        try{
            $task = $this->service->storeTask($storeTaskDto);
        } catch (StoreException $e)
        {
            return $this->failed($e->getMessage(), $e->getCode());
        }
        return $this->success($task, 201);
    }


    #[OA\Patch(
        path: '/tasks/{id}',
        summary: 'Обновить задачу',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/UpdateTaskRequest')
        ),
        tags: ['Tasks'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'ID задачи',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Задача обновлена',
                content: new OA\JsonContent(ref: '#/components/schemas/TaskSuccessResponse')
            ),
            new OA\Response(
                response: 404,
                description: 'Задача не найдена',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
            new OA\Response(
                response: 422,
                description: 'Ошибка валидации',
                content: new OA\JsonContent(ref: '#/components/schemas/ValidationErrorResponse')
            ),
        ]
    )]
    public function update(int $taskId, UpdateTaskRequest $request): JsonResponse
    {
        $dto = UpdateTaskDto::fromRequest($request);
        try {
            $task = $this->service->update($taskId, $dto);
        } catch (NotFoundException $e) {
            return $this->failed($e->getMessage(), $e->getCode());
        }

        return $this->success($task, 200);
    }

    #[OA\Delete(
        path: '/tasks/{id}',
        summary: 'Удалить задачу',
        tags: ['Tasks'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'ID задачи',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 204,
                description: 'Задача удалена'
            ),
            new OA\Response(
                response: 404,
                description: 'Задача не найдена',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
        ]
    )]
    public function destroy(int $taskId): JsonResponse
    {
        try{
            $this->service->delete($taskId);
        } catch (NotFoundException $e) {
            return $this->failed($e->getMessage(), $e->getCode());
        }

        return $this->success(null, 204);
    }

    #[OA\Post(
        path: '/tasks/{taskId}/tags/{tagId}',
        summary: 'Прикрепить тег к задаче',
        tags: ['Tasks', 'Tags'],
        parameters: [
            new OA\Parameter(
                name: 'taskId',
                description: 'ID задачи',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
            new OA\Parameter(
                name: 'tagId',
                description: 'ID тега',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Задача с обновлёнными тегами',
                content: new OA\JsonContent(ref: '#/components/schemas/TaskSuccessResponse')
            ),
            new OA\Response(
                response: 404,
                description: 'Задача или тег не найдены',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
        ]
    )]
    public function attachTag(int $taskId, int $tagId): JsonResponse
    {
        try{
            $response = $this->service->attachTag($taskId, $tagId);
        } catch (NotFoundException $e) {
            return $this->failed($e->getMessage(), $e->getCode());
        }

        return $this->success($response, 200);
    }
    #[OA\Delete(
        path: '/tasks/{taskId}/tags/{tagId}',
        summary: 'Открепить тег от задачи',
        tags: ['Tasks', 'Tags'],
        parameters: [
            new OA\Parameter(
                name: 'taskId',
                description: 'ID задачи',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
            new OA\Parameter(
                name: 'tagId',
                description: 'ID тега',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Задача с обновлёнными тегами',
                content: new OA\JsonContent(ref: '#/components/schemas/TaskSuccessResponse')
            ),
            new OA\Response(
                response: 404,
                description: 'Задача или тег не найдены',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
        ]
    )]
    public function detachTag(int $taskId, int $tagId): JsonResponse
    {
        try{
           $response = $this->service->detachTag($taskId, $tagId);
        } catch (NotFoundException $e) {
            return $this->failed($e->getMessage(), $e->getCode());
        }

        return $this->success($response, 200);
    }

    #[OA\Put(
        path: '/tasks/{taskId}/tags',
        summary: 'Синхронизировать список тегов задачи',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/SyncTagsRequest')
        ),
        tags: ['Tasks', 'Tags'],
        parameters: [
            new OA\Parameter(
                name: 'taskId',
                description: 'ID задачи',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Задача с обновлённым списком тегов',
                content: new OA\JsonContent(ref: '#/components/schemas/TaskSuccessResponse')
            ),
            new OA\Response(
                response: 404,
                description: 'Задача не найдена',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
            new OA\Response(
                response: 422,
                description: 'Ошибка валидации',
                content: new OA\JsonContent(ref: '#/components/schemas/ValidationErrorResponse')
            ),
        ]
    )]
    public function syncTags(int $taskId, SyncTagsRequest $request): JsonResponse
    {
        $syncTagDto = new SyncTagDto(
            $request->validated()['tagsIdList']
        );
        try{
            $response = $this->service->syncTags($taskId, $syncTagDto);
        } catch (NotFoundException $e) {
            return $this->failed($e->getMessage(), $e->getCode());
        }

        return $this->success($response, 200);
    }

}
