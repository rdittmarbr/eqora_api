<?php

namespace App\Http\Controllers\Admin\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TenantController extends BaseApiController
{
    public function index(Request $request): JsonResponse
    {
        return $this->error('not_implemented', 'TenantController@index not implemented.', 501);
    }

    public function store(Request $request): JsonResponse
    {
        return $this->error('not_implemented', 'TenantController@store not implemented.', 501);
    }

    public function show(Request $request, string $tenant): JsonResponse
    {
        return $this->error('not_implemented', 'TenantController@show not implemented.', 501);
    }

    public function update(Request $request, string $tenant): JsonResponse
    {
        return $this->error('not_implemented', 'TenantController@update not implemented.', 501);
    }

    public function destroy(Request $request, string $tenant): JsonResponse
    {
        return $this->error('not_implemented', 'TenantController@destroy not implemented.', 501);
    }
}
