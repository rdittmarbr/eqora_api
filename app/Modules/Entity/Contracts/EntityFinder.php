<?php

namespace App\Modules\Entity\Contracts;

interface EntityFinder
{
    public function findByDocument(string $document): ?array;
}
