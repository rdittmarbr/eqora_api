<?php

namespace App\Modules\Device\Contracts;

use App\Modules\Device\Models\Device;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface DeviceManager
{
    public function paginateByFilters(array $filters): LengthAwarePaginator;

    public function upsert(array $payload, string $ipAddress): array;

    public function findById(int $deviceId): ?Device;
}
