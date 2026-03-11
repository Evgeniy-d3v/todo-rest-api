<?php

namespace App\Application;

use App\Application\Dto\StoreTaskDto;
use App\Application\Dto\SyncTagDto;
use App\Application\Dto\UpdateTaskDto;
use App\Application\Repositories\TaskRepositoryInterface;
use App\Domain\Entities\TaskEntity;
use Illuminate\Pagination\LengthAwarePaginator;

class TaskService
{
    private const PACK_SIZE = 10;
    public function __construct(
        public TaskRepositoryInterface $taskRepository
    )
    {}
    public function getTasksList(): LengthAwarePaginator
    {
      return $this->taskRepository->getTasks(self::PACK_SIZE);
    }
    public function getTaskById(int $taskId): TaskEntity
    {
        return $this->taskRepository->getTaskById($taskId);
    }

    public function storeTask(StoreTaskDto $storeTaskDto): TaskEntity
    {
       return $this->taskRepository->store($storeTaskDto);
    }

    public function update(int $id, UpdateTaskDto $dto): TaskEntity
    {
        return $this->taskRepository->update($id, $dto);
    }

    public function delete(int $taskId): void
    {
        $this->taskRepository->deleteTask($taskId);
    }
    public function attachTag(int $taskId, int $tagId): TaskEntity
    {
       return $this->taskRepository->attachTag($taskId, $tagId);
    }

    public function detachTag(int $taskId, int $tagId): TaskEntity
    {
        return $this->taskRepository->detachTag($taskId, $tagId);
    }

    public function syncTags(int $taskId, SyncTagDto $syncTagDto): TaskEntity
    {
        return $this->taskRepository->syncTags($taskId, $syncTagDto);
    }
}
