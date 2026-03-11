<?php

namespace App\Infrastructure\Providers;

use App\Application\Repositories\TagRepositoryInterface;
use App\Application\Repositories\TaskRepositoryInterface;
use App\Infrastructure\Persistence\Repository\TagRepository;
use App\Infrastructure\Persistence\Repository\TaskRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(TagRepositoryInterface::class, TagRepository::class);
        $this->app->bind(TaskRepositoryInterface::class, TaskRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
