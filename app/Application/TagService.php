<?php

namespace App\Application;

use App\Application\Dto\StoreTagDto;
use App\Application\Dto\UpdateTagDto;
use App\Application\Repositories\TagRepositoryInterface;
use App\Domain\Entities\TagEntity;
use Illuminate\Pagination\LengthAwarePaginator;

class TagService
{

    public function __construct(
        public TagRepositoryInterface $repository
    )
    {}
    private const PACK_SIZE = 10;
    public function getTagsList():LengthAwarePaginator
    {
        return $this->repository->getTags(self::PACK_SIZE);
    }
    public function getTagById(int $taskId): TagEntity
    {
        return $this->repository->getTagById($taskId);
    }

    public function storeTag(StoreTagDto $storeTaskDto): TagEntity
    {
        return $this->repository->storeTag($storeTaskDto);
    }

    public function updateTag(int $id, UpdateTagDto $dto): TagEntity
    {
        return $this->repository->updateTag($id, $dto);
    }

    public function delete(int $taskId): void
    {
        $this->repository->deleteTag($taskId);
    }


}
