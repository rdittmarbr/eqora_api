<?php

namespace App\Modules\Entity\Services;

use App\Modules\Entity\Contracts\EntityFinder;
use App\Modules\Entity\Models\EntityCache;
use App\Modules\Entity\Services\Gateway\EntityGatewayResolver;
use App\Modules\Partner\Models\Partner;

class EntityFinderService implements EntityFinder
{
    public function __construct(private readonly EntityGatewayResolver $gatewayResolver)
    {
    }

    public function findByDocument(string $document, ?int $partnerId = null): ?array
    {
        $normalized = preg_replace('/\D/', '', $document);

        if (!$normalized) {
            return null;
        }

        $partner = $partnerId ? Partner::query()->find($partnerId) : null;

        $cached = EntityCache::query()
            ->where('document', $normalized)
            ->when($partnerId !== null, fn ($query) => $query->where('partner_id', $partnerId))
            ->latest('fetched_at')
            ->first();

        if ($cached) {
            return $cached->payload;
        }

        $gateway = $this->gatewayResolver->resolve($partner);
        $remote = $gateway->findByDocument($normalized);

        if (!$remote) {
            return null;
        }

        EntityCache::query()->updateOrCreate(
            [
                'document' => $normalized,
                'partner_id' => $partner?->id,
            ],
            [
                'source' => $gateway->sourceName(),
                'payload' => $remote,
                'fetched_at' => now(),
            ]
        );

        return $remote;
    }
}
