<?php

namespace App\Application\Repositories;

use App\Application\Dto\StoreTaskDto;
use App\Application\Dto\SyncTagDto;
use App\Application\Dto\UpdateTaskDto;
use App\Domain\Entities\TaskEntity;
use Illuminate\Pagination\LengthAwarePaginator;

interface TaskRepositoryInterface
{
    public function getTasks(int $perPage): LengthAwarePaginator;
    public function getTaskById(int $taskId): TaskEntity;
    public function store(StoreTaskDto $storeTaskDto): TaskEntity;
    public function update(int $id, UpdateTaskDto $dto): TaskEntity;
    public function deleteTask(int $taskId): void;
    public function attachTag(int $taskId, int $tagId): TaskEntity;
    public function detachTag(int $taskId, int $tagId);
    public function syncTags(int $taskId, SyncTagDto $syncTagDto): TaskEntity;

}
