<?php

namespace App\Domain\Entities;

final class TagEntity implements \JsonSerializable
{
    public function __construct(
    private int $id,
    private string $description,
    private array $tasks = [],
){}

    public function getId(): int
    {
        return $this->id;
    }
    public function getDescription(): string
    {
    return $this->description;
    }
    public function getTasks(): array
{
    return $this->tasks;
}

    public function toArray(): array
{
    return [
        'id' => $this->id,
        'description' => $this->description,
        'tasks' => $this->tasks,
    ];
}

    public function jsonSerialize(): mixed
{
    return $this->toArray();
}
}

