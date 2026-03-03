<?php

namespace App\Models;

use Illuminate\Support\Collection;

class Property
{
    private const DATASET = [
        [
            'registration' => 'CUR-001-XYZ',
            'owner_document' => '12345678901',
            'owner_name' => 'João da Silva',
            'address' => [
                'street' => 'Rua XV de Novembro',
                'number' => '456',
                'district' => 'Centro',
                'city' => 'Curitiba',
                'state' => 'PR',
                'postal_code' => '80000000',
            ],
            'characteristics' => [
                'type' => 'residential',
                'area_m2' => 150.75,
                'construction_year' => 2015,
            ],
            'updated_at' => '2025-01-11T09:00:00Z',
        ],
        [
            'registration' => 'CUR-002-ABC',
            'owner_document' => '98765432100',
            'owner_name' => 'Maria Oliveira',
            'address' => [
                'street' => 'Avenida Sete de Setembro',
                'number' => '12',
                'district' => 'Batel',
                'city' => 'Curitiba',
                'state' => 'PR',
                'postal_code' => '80010001',
            ],
            'characteristics' => [
                'type' => 'commercial',
                'area_m2' => 310.10,
                'construction_year' => 2005,
            ],
            'updated_at' => '2025-01-08T17:40:00Z',
        ],
    ];

    public static function findByRegistration(string $registration): ?array
    {
        $normalized = strtoupper(trim($registration));
        return collect(self::DATASET)
            ->first(fn ($item) => strtoupper($item['registration']) === $normalized);
    }

    public static function all(): Collection
    {
        return collect(self::DATASET);
    }
}
