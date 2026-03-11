<?php

namespace App\Modules\Device\Services;

use App\Modules\Device\Contracts\DeviceManager;
use App\Modules\Device\Models\Device;
use App\Modules\Device\Models\DeviceConnectionLog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DeviceManagerService implements DeviceManager
{
    public function paginateByFilters(array $filters): LengthAwarePaginator
    {
        $query = Device::query();

        if (array_key_exists('tenant_id', $filters) && $filters['tenant_id'] !== null) {
            $query->where('tenant_id', $filters['tenant_id']);
        }

        if (array_key_exists('partner_id', $filters) && $filters['partner_id'] !== null) {
            $query->where('partner_id', $filters['partner_id']);
        }

        if (array_key_exists('is_active', $filters) && $filters['is_active'] !== null) {
            $query->where('is_active', $filters['is_active']);
        }

        if (array_key_exists('device_uuid', $filters) && $filters['device_uuid']) {
            $query->where('device_uuid', $filters['device_uuid']);
        }

        $perPage = $filters['per_page'] ?? 20;

        return $query->orderBy('id', 'desc')->paginate($perPage);
    }

    public function upsert(array $payload, string $ipAddress): array
    {
        $record = Device::query()
            ->where('device_uuid', $payload['device_uuid'])
            ->first();

        if (!$record) {
            $record = Device::query()->create(array_merge($payload, [
                'ip_address' => $ipAddress,
                'connected_ip' => null,
                'last_seen_at' => now(),
                'connected_at' => null,
                'disconnected_at' => now(),
                'is_active' => false,
                'is_blocked' => true,
                'blocked_reason' => 'device_pending_activation',
                'blocked_at' => now(),
            ]));

            $this->createConnectionLog($record, $ipAddress, 'blocked');

            return [
                'record' => $record,
                'created' => true,
                'blocked' => true,
                'active' => false,
            ];
        }

        // Existing records must not be updated by POST /devices.
        $this->createConnectionLog($record, $ipAddress, 'validation');

        return [
            'record' => $record,
            'created' => false,
            'blocked' => (bool) $record->is_blocked,
            'active' => (bool) $record->is_active,
        ];
    }

    public function findById(int $deviceId): ?Device
    {
        return Device::query()->find($deviceId);
    }

    private function createConnectionLog(Device $device, string $ipAddress, string $event): void
    {
        DeviceConnectionLog::query()->create([
            'device_id' => $device->id,
            'tenant_id' => $device->tenant_id,
            'partner_id' => $device->partner_id,
            'event' => $event,
            'ip_address' => $ipAddress,
            'is_active' => $device->is_active,
            'payload' => [
                'device_type' => $device->device_type,
                'platform' => $device->platform,
                'is_blocked' => $device->is_blocked,
            ],
        ]);
    }
}
