<?php

namespace App\Infrastructure\Persistence\Models;

use App\Domain\Entities\TaskPriorityEnum;
use App\Domain\Entities\TaskStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Collection;


/**
 * {@inheritDoc}
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property TaskStatusEnum $status
 * @property TaskPriorityEnum $priority
 * @property int $due_at
 * @property Collection<Tag> $tags
 */
class Task extends Model
{
    protected $table = 'tasks';

    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'due_at',
    ];
    protected $casts = [
        'title' => 'string',
        'description' => 'string',
        'status' => TaskStatusEnum::class,
        'priority' => TaskPriorityEnum::class,
        'due_at' => 'string'
    ];


    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(
            Tag::class,
            'tags_tasks',
            'task_id',
            'tag_id'
        );
    }
}
