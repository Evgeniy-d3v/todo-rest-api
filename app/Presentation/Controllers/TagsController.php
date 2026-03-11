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

class TagsController extends BaseApiController
{
    public function __construct(
        public TagService $service
    )
    {}

    public function index(): JsonResponse
    {
        $response = $this->service->getTagsList();
        return  new JsonResponse(
            $response
        );
    }

    public function show(int $tagId): JsonResponse
    {
        try{
            $response = $this->service->getTagById($tagId);
        } catch (NotFoundException $e) {
            return $this->failed($e->getMessage(), $e->getCode());
        }
        return new JsonResponse($response);
    }
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
