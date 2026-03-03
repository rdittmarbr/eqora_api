<?php

namespace App\Http\Controllers\Admin\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CityHallCallbackController extends BaseApiController
{
    public function handle(Request $request): JsonResponse
    {
        Log::info('City hall callback received', $request->all());

        return $this->success(['message' => 'acknowledged']);
    }
}
