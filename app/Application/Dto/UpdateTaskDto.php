<?php

namespace App\Application\Dto;

use App\Domain\Entities\TaskPriorityEnum;
use App\Domain\Entities\TaskStatusEnum;
use App\Presentation\Requests\UpdateTaskRequest;

final class UpdateTaskDto
{
    public function __construct(
        public readonly ?string $title,
        public readonly ?string $description,
        public readonly ?TaskStatusEnum $status,
        public readonly ?TaskPriorityEnum $priority,
        public readonly ?string $due_at,
    )
    {

    }
    public static function fromRequest(UpdateTaskRequest $request): self
    {
        $data = $request->validated();

        return new self(
            $data['title'] ?? null,
            $data['description'] ?? null,
            isset($data['status']) ? TaskStatusEnum::from($data['status']) : null,
            isset($data['priority']) ? TaskPriorityEnum::from($data['priority']) : null,
            $data['due_at'] ?? null,
        );
    }
}
