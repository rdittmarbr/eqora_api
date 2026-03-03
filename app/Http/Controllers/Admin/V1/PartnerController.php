<?php

namespace App\Http\Controllers\Admin\V1;

use App\Modules\Partner\Contracts\PartnerManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PartnerController extends BaseApiController
{
    public function __construct(private readonly PartnerManager $partnerManager)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'tenant_id' => ['required', 'integer', 'exists:tenants,id'],
            'status' => ['nullable', 'string', 'max:32'],
            'document' => ['nullable', 'string', 'max:20'],
            'per_page' => ['nullable', 'integer', 'between:1,100'],
        ]);

        $partners = $this->partnerManager->paginateByFilters($validated);

        return $this->success([
            'data' => $partners->items(),
            'meta' => [
                'current_page' => $partners->currentPage(),
                'last_page' => $partners->lastPage(),
                'per_page' => $partners->perPage(),
                'total' => $partners->total(),
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'tenant_id' => ['required', 'integer', 'exists:tenants,id'],
            'name' => ['required', 'string', 'max:255'],
            'document' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'status' => ['nullable', 'string', 'max:32'],
            'metadata' => ['nullable', 'array'],
        ]);

        if ($this->partnerManager->existsByTenantAndDocument($payload['tenant_id'], $payload['document'])) {
            return $this->error('partner_already_exists', 'Partner already exists for this tenant/document.', 409);
        }

        $partner = $this->partnerManager->create($payload);

        return $this->success(['data' => $partner], 201);
    }

    public function show(Request $request, int $partner): JsonResponse
    {
        $validated = $request->validate([
            'tenant_id' => ['required', 'integer', 'exists:tenants,id'],
        ]);

        $record = $this->partnerManager->findByTenantAndId($validated['tenant_id'], $partner);

        if (!$record) {
            return $this->error('partner_not_found', 'Partner was not found for the supplied tenant.', 404);
        }

        return $this->success(['data' => $record]);
    }

    public function update(Request $request, int $partner): JsonResponse
    {
        $payload = $request->validate([
            'tenant_id' => ['required', 'integer', 'exists:tenants,id'],
            'name' => ['sometimes', 'string', 'max:255'],
            'document' => ['sometimes', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'status' => ['sometimes', 'string', 'max:32'],
            'metadata' => ['nullable', 'array'],
        ]);

        $record = $this->partnerManager->findByTenantAndId($payload['tenant_id'], $partner);

        if (!$record) {
            return $this->error('partner_not_found', 'Partner was not found for the supplied tenant.', 404);
        }

        if (isset($payload['document'])) {
            if ($this->partnerManager->hasDuplicateDocument($payload['tenant_id'], $payload['document'], (int) $record->id)) {
                return $this->error('partner_already_exists', 'Another partner already uses this document in the same tenant.', 409);
            }
        }

        unset($payload['tenant_id']);
        $record = $this->partnerManager->update($record, $payload);

        return $this->success(['data' => $record]);
    }

    public function destroy(Request $request, int $partner): JsonResponse
    {
        $validated = $request->validate([
            'tenant_id' => ['required', 'integer', 'exists:tenants,id'],
        ]);

        $record = $this->partnerManager->findByTenantAndId($validated['tenant_id'], $partner);

        if (!$record) {
            return $this->error('partner_not_found', 'Partner was not found for the supplied tenant.', 404);
        }

        $this->partnerManager->delete($record);

        return $this->success(['message' => 'Partner removed successfully.']);
    }
}
