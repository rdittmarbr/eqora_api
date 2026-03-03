<?php

namespace App\Http\Controllers\Client\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EntityLoginEventController extends BaseApiController
{
    public function index(Request $request, string $entity): JsonResponse
    {
        return $this->error('not_implemented', 'EntityLoginEventController@index not implemented.', 501);
    }

    public function store(Request $request, string $entity): JsonResponse
    {
        return $this->error('not_implemented', 'EntityLoginEventController@store not implemented.', 501);
    }
}
