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


class TasksController extends BaseApiController
{
    public function __construct(
        public TaskService $service
    )
    {}
    public function index(): JsonResponse
    {
        $response = $this->service->getTasksList();
        return new JsonResponse(
            $response
        );
    }
    public function show(int $taskId): JsonResponse
    {
        try{
            $response = $this->service->getTaskById($taskId);
        } catch (NotFoundException $e) {
            return $this->failed($e->getMessage(), $e->getCode());
        }
        return new JsonResponse($response);
    }
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

    public function destroy(int $taskId): JsonResponse
    {
        try{
            $this->service->delete($taskId);
        } catch (NotFoundException $e) {
            return $this->failed($e->getMessage(), $e->getCode());
        }

        return $this->success(null, 204);
    }

    public function attachTag(int $taskId, int $tagId): JsonResponse
    {
        try{
            $response = $this->service->attachTag($taskId, $tagId);
        } catch (NotFoundException $e) {
            return $this->failed($e->getMessage(), $e->getCode());
        }

        return $this->success($response, 200);
    }
    public function detachTag(int $taskId, int $tagId): JsonResponse
    {
        try{
           $response = $this->service->detachTag($taskId, $tagId);
        } catch (NotFoundException $e) {
            return $this->failed($e->getMessage(), $e->getCode());
        }

        return $this->success($response, 200);
    }

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
