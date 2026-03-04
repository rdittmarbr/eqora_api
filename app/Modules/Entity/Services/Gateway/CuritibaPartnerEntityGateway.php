<?php

namespace App\Modules\Entity\Services\Gateway;

use App\Modules\Entity\Contracts\PartnerEntityGateway;
use App\Modules\Partner\Models\Partner;
use Illuminate\Support\Facades\Http;

class CuritibaPartnerEntityGateway implements PartnerEntityGateway
{
    public function supports(?Partner $partner): bool
    {
        if (!$partner) {
            return false;
        }

        return ($partner->metadata['entity_gateway'] ?? null) === 'curitiba';
    }

    public function sourceName(): string
    {
        return 'curitiba-child-api';
    }

    public function findByDocument(string $document): ?array
    {
        $baseUrl = (string) config('services.entity_gateways.curitiba.base_url');

        if ($baseUrl === '') {
            return null;
        }

        $response = Http::baseUrl($baseUrl)
            ->timeout((int) config('services.entity_gateways.curitiba.timeout', 5))
            ->acceptJson()
            ->get('/entities/'.$document);

        if (!$response->successful()) {
            return null;
        }

        $payload = $response->json();

        if (!is_array($payload)) {
            return null;
        }

        return $payload['data'] ?? null;
    }
}
