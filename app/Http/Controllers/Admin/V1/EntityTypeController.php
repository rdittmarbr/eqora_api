<?php

namespace App\Http\Controllers\Admin\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EntityTypeController extends BaseApiController
{
    public function index(Request $request): JsonResponse
    {
        return $this->error('not_implemented', 'EntityTypeController@index not implemented.', 501);
    }

    public function store(Request $request): JsonResponse
    {
        return $this->error('not_implemented', 'EntityTypeController@store not implemented.', 501);
    }

    public function show(Request $request, string $entityType): JsonResponse
    {
        return $this->error('not_implemented', 'EntityTypeController@show not implemented.', 501);
    }

    public function update(Request $request, string $entityType): JsonResponse
    {
        return $this->error('not_implemented', 'EntityTypeController@update not implemented.', 501);
    }

    public function destroy(Request $request, string $entityType): JsonResponse
    {
        return $this->error('not_implemented', 'EntityTypeController@destroy not implemented.', 501);
    }
}
