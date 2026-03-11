<?php

namespace App\Presentation\Controllers;

use Illuminate\Http\JsonResponse;

abstract class BaseApiController
{
    protected function success(mixed $data = null, int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $data,
        ], $status);
    }

    protected function failed(mixed $data = null, int $status = 404): JsonResponse
    {
        return response()->json([
            'success' => false,
            'data' => $data,
        ], $status);
    }
}
