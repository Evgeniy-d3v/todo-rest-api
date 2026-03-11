<?php

namespace Database\Seeders;


use App\Domain\Entities\TaskPriorityEnum;
use App\Domain\Entities\TaskStatusEnum;
use App\Infrastructure\Persistence\Models\Task;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $i = 0;
        do {
            Task::create([
                'title' => 'задача ' . $i,
                'description' => 'Описание задачи ' . $i,
                'status' => TaskStatusEnum::NEW,
                'priority' => TaskPriorityEnum::HIGH,
                'due_at' => now()->addDay()->timestamp,
            ]);
            $i++;
        } while ($i < 10);
    }
}
