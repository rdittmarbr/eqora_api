<?php

namespace App\Http\Controllers\Client\V1;

use App\Modules\Entity\Contracts\EntityFinder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EntityController extends BaseApiController
{
    public function __construct(private readonly EntityFinder $entityFinder)
    {
    }

    /**
     * Retrieve an entity (person/company) by CPF/CNPJ.
     */
    public function show(Request $request, string $document): JsonResponse
    {
        $partnerId = $this->resolvePartnerId($request);
        $record = $this->entityFinder->findByDocument($document, $partnerId);

        if (!$record) {
            return $this->error('entity_not_found', 'Entity not found for supplied document.', 404);
        }

        $normalized = preg_replace('/\D/', '', $document);

        return $this->success([
            'data' => [
                'document' => [
                    'original' => $normalized,
                    'masked' => $this->maskDocument($normalized),
                ],
                'type' => $record['type'],
                'name' => $record['name'],
                'email' => $record['email'],
                'phone' => $record['phone'],
                'rg' => $record['rg'] ?? null,
                'address' => $record['address'],
                'updated_at' => $record['updated_at'],
            ],
        ]);
    }

    private function resolvePartnerId(Request $request): ?int
    {
        $partnerId = $request->input('partner_id', $request->header('X-Partner-Id'));

        return is_numeric($partnerId) ? (int) $partnerId : null;
    }

    private function maskDocument(?string $document): ?string
    {
        if (empty($document)) {
            return null;
        }

        $length = strlen($document);

        if ($length === 11) {
            return substr($document, 0, 3) . '***' . substr($document, -3);
        }

        if ($length === 14) {
            return substr($document, 0, 4) . '*****' . substr($document, -4);
        }

        return str_repeat('*', max($length - 4, 0)) . substr($document, -4);
    }
}
