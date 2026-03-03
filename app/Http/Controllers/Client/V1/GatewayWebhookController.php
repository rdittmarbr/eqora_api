<?php

namespace App\Http\Controllers\Client\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GatewayWebhookController extends BaseApiController
{
    public function handle(Request $request): JsonResponse
    {
        $payload = $request->all();
        Log::info('Gateway webhook received', $payload);

        return $this->success(['message' => 'received']);
    }
}
