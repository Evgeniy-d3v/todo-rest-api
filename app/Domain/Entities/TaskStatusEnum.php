<?php

namespace App\Domain\Entities;

enum TaskStatusEnum: string
{
    case NEW = 'new';
    case IN_PROGRESS = 'in_progress';
    case DONE = 'done';
}
