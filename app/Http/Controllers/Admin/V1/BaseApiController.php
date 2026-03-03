<?php

namespace App\Http\Controllers\Admin\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

abstract class BaseApiController extends Controller
{
    protected function success(array $payload = [], int $status = 200): JsonResponse
    {
        return response()->json(array_merge(['status' => true], $payload), $status);
    }

    protected function error(string $code, string $message, int $status = 400, array $meta = []): JsonResponse
    {
        return response()->json(array_merge([
            'status' => false,
            'error' => [
                'code' => $code,
                'message' => $message,
            ],
        ], $meta), $status);
    }
}
