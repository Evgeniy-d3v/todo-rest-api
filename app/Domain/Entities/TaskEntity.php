<?php

namespace App\Domain\Entities;

final class TaskEntity implements \JsonSerializable
{

    public function __construct(
        private int $id,
        private string $title,
        private string $description,
        private TaskStatusEnum $status,
        private TaskPriorityEnum $priority,
        private int $dueAt,
        private array $tags = [],
    ){}

    public function getId(): int
    {
        return $this->id;
    }
    public function getTitle(): string
    {
        return $this->title;
    }
    public function getDescription(): string
    {
        return $this->description;
    }
    public function getStatus(): string
    {
        return $this->status->value;
    }
    public function getPriority(): string
    {
        return $this->priority->value;
    }

    public function getDueAt(): int
    {
        return $this->dueAt;
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status->value,
            'priority' => $this->priority->value,
            'due_at' => $this->dueAt,
            'tags' => $this->tags,
        ];
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }
}
