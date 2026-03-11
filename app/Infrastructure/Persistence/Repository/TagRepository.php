<?php

namespace App\Infrastructure\Persistence\Repository;

use App\Application\Dto\StoreTagDto;
use App\Application\Dto\UpdateTagDto;
use App\Application\Repositories\TagRepositoryInterface;
use App\Domain\Entities\TagEntity;
use App\Domain\Exception\NotFoundException;
use App\Domain\Exception\StoreException;
use App\Infrastructure\Persistence\Models\Tag;
use Illuminate\Pagination\LengthAwarePaginator;

class TagRepository implements TagRepositoryInterface
{
    public function getTags(int $packSize): LengthAwarePaginator
    {
        $paginator = Tag::with('tasks')
            ->orderByDesc('id')
            ->paginate($packSize);

        $paginator->setCollection(
            $paginator->getCollection()
                ->map(fn (Tag $tag) => $this->toDomainEntity($tag))
        );

        return $paginator;
    }

    public function getTagById(int $tagId): TagEntity
    {
        $task = Tag::with('tasks')->find($tagId);
        if ($task === null) {
            throw new NotFoundException("Тег с айди $tagId не найдена", 404);
        }
        return $this->toDomainEntity($task);
    }


    public function storeTag(StoreTagDto $storeTaskDto): TagEntity
    {
//        try {
            $task = Tag::create([
                'description' => $storeTaskDto->description,
            ]);
//        } catch (\Throwable $e) {
//            throw new StoreException('Что-то пошло не так при создании тега', 500, $e);
//        }
        return $this->toDomainEntity($task);
    }

    public function updateTag(int $id, UpdateTagDto $dto): TagEntity
    {
        $tag = Tag::find($id);
        if ($tag === null) {
            throw new NotFoundException("Задача с айди $id не найдена", 404);
        }
        $data['description'] = $dto->description;
        $tag->update($data);

        return $this->toDomainEntity($tag);
    }


    public function deleteTag(int $taskId): void
    {
        $task = Tag::find($taskId);

        if ($task === null) {
            throw new NotFoundException("Задача с айди $taskId не найдена", 404);
        }

        $task->delete();
    }
    private function toDomainEntity(Tag $tag): TagEntity
    {
        return new TagEntity(
            $tag->id,
            $tag->description,
            $tag->tasks->toArray(),
        );
    }

}
