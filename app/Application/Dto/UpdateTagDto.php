<?php

namespace App\Application\Dto;

final class UpdateTagDto
{
    public function __construct(
        public readonly string $description,
    )
    {}
}
