<?php
use App\Presentation\Controllers\TasksController;
use Illuminate\Support\Facades\Route;

Route::prefix('tasks')->group(function () {

    Route::get('/', [TasksController::class, 'index']);
    Route::get('/{id}', [TasksController::class, 'show']);
    Route::post('/', [TasksController::class, 'store']);
    Route::patch('/{id}', [TasksController::class, 'update']);
    Route::delete('/{id}', [TasksController::class, 'destroy']);

    Route::post('/{taskId}/tags/{tagId}', [TasksController::class, 'attachTag']);
    Route::delete('/{taskId}/tags/{tagId}', [TasksController::class, 'detachTag']);
    Route::put('/{taskId}/tags', [TasksController::class, 'syncTags']);
});
