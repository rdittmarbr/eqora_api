<?php

namespace App\Http\Controllers\Client\V1;

use App\Modules\Device\Contracts\DeviceManager;
use App\Modules\Device\Models\Device;
use App\Modules\Partner\Models\Partner;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DeviceController extends BaseApiController
{
    public function __construct(private readonly DeviceManager $deviceManager)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $filters = [
            'tenant_id' => $this->toNullableInt($request->input('tenant_id')),
            'partner_id' => $this->toNullableInt($request->input('partner_id')),
            'is_active' => $this->toNullableBool($request->input('is_active')),
            'device_uuid' => $request->input('device_uuid'),
            'per_page' => $this->toNullableInt($request->input('per_page')),
        ];

        $devices = $this->deviceManager->paginateByFilters($filters);
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
        $payload = [
            'tenant_id' => $this->toNullableInt($request->input('tenant_id')),
            'partner_id' => $this->toNullableInt($request->input('partner_id')),
            'device_uuid' => (string) $request->input('device_uuid', ''),
            'device_type' => (string) $request->input('device_type', ''),
            'device_name' => $request->input('device_name'),
            'name' => $request->input('name'),
            'platform' => (string) $request->input('platform', ''),
            'app_version' => $request->input('app_version'),
            'browser_name' => $request->input('browser_name'),
            'browser_version' => $request->input('browser_version'),
            'specifications' => $request->input('specifications'),
            'metadata' => $request->input('metadata'),
            'is_active' => $this->toNullableBool($request->input('is_active')),
        ];

        if ($payload['device_uuid'] === '' || $payload['platform'] === '' || $payload['device_type'] === '') {
            return $this->deviceValidationResponse(
                false,
                'Falha ao carregar os dados.',
                false,
                $this->emptyPartners(),
                'device_uuid, platform and device_type are required.',
                422
            );
        }

        if (!in_array($payload['device_type'], ['android', 'browser'], true)) {
            return $this->deviceValidationResponse(
                false,
                'Falha ao carregar os dados.',
                false,
                $this->emptyPartners(),
                'device_type must be android or browser.',
                422
            );
        }

        $result = $this->deviceManager->upsert($payload, (string) $request->ip());
        /** @var Device $record */
        $record = $result['record'];
        $partners = $this->resolvePartners($record);

        if ($result['blocked'] ?? false) {
            return $this->deviceValidationResponse(
                false,
                'Falha ao carregar os dados.',
                false,
                $partners,
                'Device is blocked.',
                423
            );
        }

        if (!(bool) ($result['active'] ?? false)) {
            return $this->deviceValidationResponse(
                false,
                'Falha ao carregar os dados.',
                false,
                $partners,
                'Device is inactive.',
                403
            );
        }

        return $this->deviceValidationResponse(
            true,
            'Dados carregados com sucesso.',
            true,
            $partners,
            null,
            200
        );
    }

    public function show(Request $request, int $device): JsonResponse
    {
        $record = $this->deviceManager->findById($device);

        if (!$record) {
            return $this->error('device_not_found', 'Device was not found.', 404);
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

    private function toNullableInt(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        return is_numeric($value) ? (int) $value : null;
    }

    private function toNullableBool(mixed $value): ?bool
    {
        if ($value === null || $value === '') {
            return null;
        }

        return filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    }

    private function deviceValidationResponse(
        bool $status,
        string $message,
        bool $enabled,
        array $partners,
        mixed $error,
        int $httpStatus
    ): JsonResponse {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'enabled' => $enabled,
            'partners' => $partners,
            'error' => $error,
        ], $httpStatus);
    }

    private function resolvePartners(Device $device): array
    {
        $device->loadMissing('partner');

        /** @var Partner|null $partner */
        $partner = $device->partner;
        if (!$partner) {
            return $this->emptyPartners();
        }

        return [[
            'id' => $partner->id !== null ? (string) $partner->id : null,
            'name' => $partner->name !== null ? (string) $partner->name : null,
        ]];
    }

    private function emptyPartners(): array
    {
        return [[
            'id' => null,
            'name' => null,
        ]];
    }
}
