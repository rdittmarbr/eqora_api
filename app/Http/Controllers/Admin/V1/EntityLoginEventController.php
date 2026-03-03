<?php

namespace App\Http\Controllers\Admin\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EntityLoginEventController extends BaseApiController
{
    public function index(Request $request): JsonResponse
    {
        return $this->error('not_implemented', 'EntityLoginEventController@index not implemented.', 501);
    }

    public function store(Request $request): JsonResponse
    {
        return $this->error('not_implemented', 'EntityLoginEventController@store not implemented.', 501);
    }

    public function show(Request $request, string $event): JsonResponse
    {
        return $this->error('not_implemented', 'EntityLoginEventController@show not implemented.', 501);
    }
}
