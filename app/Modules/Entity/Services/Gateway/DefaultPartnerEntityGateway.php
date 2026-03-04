<?php

namespace App\Modules\Entity\Services\Gateway;

use App\Modules\Entity\Contracts\PartnerEntityGateway;
use App\Modules\Entity\Models\Entity;
use App\Modules\Partner\Models\Partner;

class DefaultPartnerEntityGateway implements PartnerEntityGateway
{
    public function supports(?Partner $partner): bool
    {
        return true;
    }

    public function sourceName(): string
    {
        return 'default-child-api';
    }

    public function findByDocument(string $document): ?array
    {
        // Temporary adapter for child API project while integration endpoint is not configured.
        return Entity::findByDocument($document);
    }
}
