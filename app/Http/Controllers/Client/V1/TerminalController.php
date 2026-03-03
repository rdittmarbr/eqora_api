<?php

namespace App\Http\Controllers\Client\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TerminalController extends BaseApiController
{
    public function index(Request $request): JsonResponse
    {
        return $this->error('not_implemented', 'TerminalController@index not implemented.', 501);
    }

    public function store(Request $request): JsonResponse
    {
        return $this->error('not_implemented', 'TerminalController@store not implemented.', 501);
    }

    public function show(Request $request, string $terminal): JsonResponse
    {
        return $this->error('not_implemented', 'TerminalController@show not implemented.', 501);
    }

    public function heartbeat(Request $request, string $terminal): JsonResponse
    {
        return $this->error('not_implemented', 'TerminalController@heartbeat not implemented.', 501);
    }
}
