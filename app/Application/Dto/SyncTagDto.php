<?php

namespace App\Application\Dto;

final class SyncTagDto
{
    public function __construct(
        public readonly array $tagsIdList,
    )
    {

    }
}
