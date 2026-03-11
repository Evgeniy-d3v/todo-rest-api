<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    require base_path('routes/api/tasks.php');
    require base_path('routes/api/tags.php');
});

