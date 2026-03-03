<?php

namespace App\Http\Controllers\Admin\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LogController extends BaseApiController
{
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['status', 'transaction_id', 'correlation_id']);

        return $this->success([
            'filters' => $filters,
            'data' => [
                [
                    'id' => 1,
                    'status' => 'success',
                    'transaction_id' => 'trx-001',
                    'correlation_id' => 'corr-123',
                    'timestamp' => now()->subMinutes(2)->toIso8601String(),
                ],
            ],
        ]);
    }
}
