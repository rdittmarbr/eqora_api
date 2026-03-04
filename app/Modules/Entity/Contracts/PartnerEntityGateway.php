<?php

namespace App\Modules\Entity\Contracts;

use App\Modules\Partner\Models\Partner;

interface PartnerEntityGateway
{
    public function supports(?Partner $partner): bool;

    public function sourceName(): string;

    public function findByDocument(string $document): ?array;
}
