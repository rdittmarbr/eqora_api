<?php

namespace App\Modules\Partner\Contracts;

use App\Modules\Partner\Models\Partner;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface PartnerManager
{
    public function paginateByFilters(array $filters): LengthAwarePaginator;

    public function existsByTenantAndDocument(int $tenantId, string $document): bool;

    public function create(array $payload): Partner;

    public function findByTenantAndId(int $tenantId, int $partnerId): ?Partner;

    public function hasDuplicateDocument(int $tenantId, string $document, int $exceptId): bool;

    public function update(Partner $partner, array $payload): Partner;

    public function delete(Partner $partner): void;

    public function belongsToTenant(int $tenantId, int $partnerId): bool;
}
