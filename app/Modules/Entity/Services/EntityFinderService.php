<?php

namespace App\Modules\Entity\Services;

use App\Modules\Entity\Contracts\EntityFinder;
use App\Modules\Entity\Models\Entity;

class EntityFinderService implements EntityFinder
{
    public function findByDocument(string $document): ?array
    {
        return Entity::findByDocument($document);
    }
}
