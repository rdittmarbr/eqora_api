<?php

namespace App\Http\Controllers\Client\V1;

use Illuminate\Http\JsonResponse;

class OperationStatusController extends BaseApiController
{
    public function show(string $operation): JsonResponse
    {
        return $this->success([
            'operation_id' => $operation,
            'status' => 'processing',
            'updated_at' => now()->toIso8601String(),
        ]);
    }
}
