<?php

namespace App\Application\Dto;

final class StoreTaskDto
{
    public function __construct(
        public readonly string $title,
        public readonly string $description,
        public readonly string $status,
        public readonly string $priority,
        public readonly string $due_at,
    )
    {

    }
}
