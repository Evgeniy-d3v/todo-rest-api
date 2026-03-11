<?php

namespace App\Application\Dto;

final class StoreTagDto
{
    public function __construct(
        public readonly string $description,
    )
    {}
}
