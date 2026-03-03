<?php

namespace App\Http\Controllers\Client\V1;

use App\Modules\Device\Contracts\DeviceManager;
use App\Modules\Device\Models\Device;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DeviceController extends BaseApiController
{
    public function __construct(private readonly DeviceManager $deviceManager)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'tenant_id' => ['required', 'integer', 'exists:tenants,id'],
            'partner_id' => ['nullable', 'integer', 'exists:partners,id'],
            'is_active' => ['nullable', 'boolean'],
            'per_page' => ['nullable', 'integer', 'between:1,100'],
        ]);

        $devices = $this->deviceManager->paginateByFilters($validated);
        $data = $devices->getCollection()
            ->map(fn (Device $device) => $this->serializeDevice($device))
            ->values()
            ->all();

        return $this->success([
            'data' => $data,
            'meta' => [
                'current_page' => $devices->currentPage(),
                'last_page' => $devices->lastPage(),
                'per_page' => $devices->perPage(),
                'total' => $devices->total(),
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'tenant_id' => ['required', 'integer', 'exists:tenants,id'],
            'partner_id' => ['required', 'integer', 'exists:partners,id'],
            'device_uuid' => ['required', 'string', 'max:120'],
            'name' => ['required', 'string', 'max:255'],
            'platform' => ['required', 'string', 'max:40'],
            'app_version' => ['nullable', 'string', 'max:40'],
            'is_active' => ['nullable', 'boolean'],
            'metadata' => ['nullable', 'array'],
        ]);

        if (!$this->deviceManager->partnerBelongsToTenant($payload['tenant_id'], $payload['partner_id'])) {
            return $this->error('partner_not_found', 'Partner does not belong to the supplied tenant.', 422);
        }

        $result = $this->deviceManager->upsert($payload, (string) $request->ip());
        /** @var Device $record */
        $record = $result['record'];
        $created = (bool) $result['created'];

        return $this->success(['data' => $this->serializeDevice($record)], $created ? 201 : 200);
    }

    public function show(Request $request, int $device): JsonResponse
    {
        $validated = $request->validate([
            'tenant_id' => ['required', 'integer', 'exists:tenants,id'],
        ]);

        $record = $this->deviceManager->findByTenantAndId($validated['tenant_id'], $device);

        if (!$record) {
            return $this->error('device_not_found', 'Device was not found for the supplied tenant.', 404);
        }

        return $this->success(['data' => $this->serializeDevice($record)]);
    }

    private function serializeDevice(Device $device): array
    {
        $data = $device->toArray();

        $connectedForSeconds = null;
        if ($device->is_active && $device->connected_at) {
            $connectedForSeconds = now()->diffInSeconds($device->connected_at);
        }

        $data['connection'] = [
            'connected_ip' => $device->is_active ? $device->connected_ip : null,
            'connected_at' => $device->connected_at?->toIso8601String(),
            'disconnected_at' => $device->disconnected_at?->toIso8601String(),
            'connected_for_seconds' => $connectedForSeconds,
        ];

        return $data;
    }
}
