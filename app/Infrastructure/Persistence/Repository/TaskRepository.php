<?php

namespace App\Infrastructure\Persistence\Repository;

use App\Application\Dto\StoreTaskDto;
use App\Application\Dto\SyncTagDto;
use App\Application\Dto\UpdateTaskDto;
use App\Application\Repositories\TaskRepositoryInterface;
use App\Domain\Entities\TagEntity;
use App\Domain\Entities\TaskEntity;
use App\Domain\Exception\NotFoundException;
use App\Domain\Exception\StoreException;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Infrastructure\Persistence\Models\Task;
use Carbon\Carbon;


class TaskRepository implements TaskRepositoryInterface
{
    public function getTasks(int $perPage): LengthAwarePaginator
    {
        $paginator = Task::with('tags')
            ->orderByDesc('id')
            ->paginate($perPage);

        $paginator->setCollection(
            $paginator->getCollection()
                ->map(fn (Task $task) => $this->toDomainEntity($task))
        );

        return $paginator;
    }

    public function getTaskById(int $taskId): TaskEntity
    {
        $task = Task::with('tags')->find($taskId);
        if ($task === null) {
            throw new NotFoundException("Задача с айди $taskId не найдена", 404);
        }
        return $this->toDomainEntity($task);
    }


    public function store(StoreTaskDto $storeTaskDto): TaskEntity
    {
        try {
            $task = Task::create([
                'title' => $storeTaskDto->title,
                'description' => $storeTaskDto->description,
                'status' => $storeTaskDto->status,
                'priority' => $storeTaskDto->priority,
                'due_at' => Carbon::parse($storeTaskDto->due_at)->timestamp,
            ]);
        } catch (\Throwable $e) {
            throw new StoreException('Что-то пошло не так при создании задачи', 500, $e);
        }
        return $this->toDomainEntity($task);
    }

    public function update(int $id, UpdateTaskDto $dto): TaskEntity
    {
        $task = Task::find($id);
        if ($task === null) {
            throw new NotFoundException("Задача с айди $id не найдена", 404);
        }
        $data = [];

        if ($dto->title !== null) {
            $data['title'] = $dto->title;
        }

        if ($dto->description !== null) {
            $data['description'] = $dto->description;
        }

        if ($dto->status !== null) {
            $data['status'] = $dto->status;
        }

        if ($dto->priority !== null) {
            $data['priority'] = $dto->priority;
        }

        if ($dto->due_at !== null) {
            $data['due_at'] = Carbon::parse($dto->due_at)->timestamp;
        }

        $task->update($data);

        return $this->toDomainEntity($task);
    }


    public function deleteTask(int $taskId): void
    {
        $task = Task::find($taskId);

        if ($task === null) {
            throw new NotFoundException("Задача с айди $taskId не найдена", 404);
        }

        $task->delete();
    }
    public function attachTag(int $taskId, int $tagId): TaskEntity
    {
        $task = Task::find($taskId);
        if ($task === null) {
            throw new NotFoundException("Задача с айди $taskId не найдена", 404);
        }
        $task->tags()->attach($tagId);
        $task->refresh();
        return $this->toDomainEntity($task);

    }
    public function detachTag(int $taskId, int $tagId): TaskEntity
    {
        $task = Task::find($taskId);
        if ($task === null) {
            throw new NotFoundException("Задача с айди $taskId не найдена", 404);
        }
        $task->tags()->detach($tagId);
        $task->refresh();
        return $this->toDomainEntity($task);
    }

    public function syncTags(int $taskId, SyncTagDto $syncTagDto): TaskEntity
    {
        $task = Task::find($taskId);
        if ($task === null) {
            throw new NotFoundException("Задача с айди $taskId не найдена", 404);
        }
        $task->tags()->sync($syncTagDto->tagsIdList);
        $task->refresh();
        return $this->toDomainEntity($task);
    }
    private function toDomainEntity(Task $task): TaskEntity
    {
        return new TaskEntity(
            $task->id,
            $task->title,
            $task->description,
            $task->status,
            $task->priority,
            $task->due_at,
            $task->tags->toArray(),
        );
    }
}
