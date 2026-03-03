<?php

namespace App\Modules\Device\Services;

use App\Modules\Device\Contracts\DeviceManager;
use App\Modules\Device\Models\Device;
use App\Modules\Partner\Contracts\PartnerManager;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DeviceManagerService implements DeviceManager
{
    public function __construct(private readonly PartnerManager $partnerManager)
    {
    }

    public function paginateByFilters(array $filters): LengthAwarePaginator
    {
        $query = Device::query()->where('tenant_id', $filters['tenant_id']);

        if (array_key_exists('partner_id', $filters)) {
            $query->where('partner_id', $filters['partner_id']);
        }

        if (array_key_exists('is_active', $filters)) {
            $query->where('is_active', $filters['is_active']);
        }

        $perPage = $filters['per_page'] ?? 20;

        return $query->orderBy('id', 'desc')->paginate($perPage);
    }

    public function partnerBelongsToTenant(int $tenantId, int $partnerId): bool
    {
        return $this->partnerManager->belongsToTenant($tenantId, $partnerId);
    }

    public function upsert(array $payload, string $ipAddress): array
    {
        $record = Device::query()->firstOrNew([
            'tenant_id' => $payload['tenant_id'],
            'device_uuid' => $payload['device_uuid'],
        ]);

        $created = !$record->exists;
        $wasActive = (bool) $record->is_active;
        $isActive = $payload['is_active'] ?? true;

        $record->fill(array_merge($payload, [
            'ip_address' => $ipAddress,
            'last_seen_at' => now(),
        ]));

        $record->is_active = $isActive;

        if ($isActive) {
            $record->connected_ip = $ipAddress;
            $record->connected_at = $wasActive && $record->connected_at ? $record->connected_at : now();
            $record->disconnected_at = null;
        } elseif ($wasActive && !$record->disconnected_at) {
            $record->disconnected_at = now();
        }

        $record->save();

        return [
            'record' => $record,
            'created' => $created,
        ];
    }

    public function findByTenantAndId(int $tenantId, int $deviceId): ?Device
    {
        return Device::query()
            ->where('tenant_id', $tenantId)
            ->find($deviceId);
    }
}
