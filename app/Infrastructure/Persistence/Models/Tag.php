<?php

namespace App\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Collection;

/**
 * {@inheritDoc}
 *
 * @property int $id
 * @property string $description
 * @property Collection<Task> $tasks
 */
class Tag extends Model
{
    protected $table = 'tags';

    protected $casts = [
        'description' => 'string',
    ];

    public function tasks(): BelongsToMany
    {
        return $this->belongsToMany(
            Task::class,
            'tags_tasks',
            'tag_id',
            'task_id'
        );
    }
}
