<?php

namespace App\Modules\Partner\Services;

use App\Modules\Partner\Contracts\PartnerManager;
use App\Modules\Partner\Models\Partner;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PartnerManagerService implements PartnerManager
{
    public function paginateByFilters(array $filters): LengthAwarePaginator
    {
        $query = Partner::query()->where('tenant_id', $filters['tenant_id']);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['document'])) {
            $query->where('document', $filters['document']);
        }

        $perPage = $filters['per_page'] ?? 20;

        return $query->orderBy('id', 'desc')->paginate($perPage);
    }

    public function existsByTenantAndDocument(int $tenantId, string $document): bool
    {
        return Partner::query()
            ->where('tenant_id', $tenantId)
            ->where('document', $document)
            ->exists();
    }

    public function create(array $payload): Partner
    {
        return Partner::create($payload);
    }

    public function findByTenantAndId(int $tenantId, int $partnerId): ?Partner
    {
        return Partner::query()
            ->where('tenant_id', $tenantId)
            ->find($partnerId);
    }

    public function hasDuplicateDocument(int $tenantId, string $document, int $exceptId): bool
    {
        return Partner::query()
            ->where('tenant_id', $tenantId)
            ->where('document', $document)
            ->where('id', '<>', $exceptId)
            ->exists();
    }

    public function update(Partner $partner, array $payload): Partner
    {
        $partner->fill($payload);
        $partner->save();

        return $partner;
    }

    public function delete(Partner $partner): void
    {
        $partner->delete();
    }

    public function belongsToTenant(int $tenantId, int $partnerId): bool
    {
        return Partner::query()
            ->where('tenant_id', $tenantId)
            ->where('id', $partnerId)
            ->exists();
    }
}
