<?php
use App\Presentation\Controllers\TagsController;
use Illuminate\Support\Facades\Route;

Route::get('/tags', [TagsController::class, 'index']);
Route::get('/tags/{id}', [TagsController::class, 'show']);
Route::post('/tags', [TagsController::class, 'store']);
Route::patch('/tags/{id}', [TagsController::class, 'update']);
Route::delete('/tags/{id}', [TagsController::class, 'destroy']);
