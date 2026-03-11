<?php

namespace App\Application\Repositories;

use App\Application\Dto\StoreTagDto;
use App\Application\Dto\UpdateTagDto;
use App\Domain\Entities\TagEntity;
use Illuminate\Pagination\LengthAwarePaginator;

interface TagRepositoryInterface
{
    public function getTags(int $packSize): LengthAwarePaginator;


    public function getTagById(int $tagId): TagEntity;


    public function storeTag(StoreTagDto $storeTaskDto): TagEntity;

    public function updateTag(int $id, UpdateTagDto $dto): TagEntity;


    public function deleteTag(int $taskId): void;

}
