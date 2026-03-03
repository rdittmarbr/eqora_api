<?php

namespace App\Http\Controllers\Admin\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PartnerTypeController extends BaseApiController
{
    public function index(Request $request): JsonResponse
    {
        return $this->error('not_implemented', 'PartnerTypeController@index not implemented.', 501);
    }

    public function store(Request $request): JsonResponse
    {
        return $this->error('not_implemented', 'PartnerTypeController@store not implemented.', 501);
    }

    public function show(Request $request, string $partnerType): JsonResponse
    {
        return $this->error('not_implemented', 'PartnerTypeController@show not implemented.', 501);
    }

    public function update(Request $request, string $partnerType): JsonResponse
    {
        return $this->error('not_implemented', 'PartnerTypeController@update not implemented.', 501);
    }

    public function destroy(Request $request, string $partnerType): JsonResponse
    {
        return $this->error('not_implemented', 'PartnerTypeController@destroy not implemented.', 501);
    }
}
