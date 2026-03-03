<?php

namespace App\Modules\Entity\Models;

use Illuminate\Support\Collection;

class Entity
{
    /**
     * Mock dataset containing both individuals and companies.
     */
    private const DATASET = [
        [
            'document' => '12345678901',
            'type' => 'natural',
            'name' => 'João da Silva',
            'email' => 'joao.silva@example.com',
            'phone' => '+55-41-99999-0000',
            'rg' => '12.345.678-9',
            'address' => [
                'street' => 'Rua XV de Novembro',
                'number' => '456',
                'district' => 'Centro',
                'city' => 'Curitiba',
                'state' => 'PR',
                'postal_code' => '80000000',
            ],
            'updated_at' => '2025-01-10T10:00:00Z',
        ],
        [
            'document' => '98765432100',
            'type' => 'natural',
            'name' => 'Maria Oliveira',
            'email' => 'maria.oliveira@example.com',
            'phone' => '+55-41-98888-0000',
            'rg' => '98.765.432-1',
            'address' => [
                'street' => 'Rua das Flores',
                'number' => '99',
                'district' => 'Batel',
                'city' => 'Curitiba',
                'state' => 'PR',
                'postal_code' => '80010000',
            ],
            'updated_at' => '2025-01-12T22:15:00Z',
        ],
        [
            'document' => '12345678000190',
            'type' => 'legal',
            'name' => 'Empresa Curitiba Serviços Ltda.',
            'email' => 'contato@curitibaservicos.com.br',
            'phone' => '+55-41-4002-8922',
            'rg' => null,
            'address' => [
                'street' => 'Avenida Sete de Setembro',
                'number' => '1200',
                'district' => 'Centro',
                'city' => 'Curitiba',
                'state' => 'PR',
                'postal_code' => '80020000',
            ],
            'updated_at' => '2025-01-05T13:30:00Z',
        ],
    ];

    public static function findByDocument(string $document): ?array
    {
        $normalized = preg_replace('/\D/', '', $document);
        return collect(self::DATASET)
            ->first(fn ($item) => $item['document'] === $normalized);
    }

    public static function all(): Collection
    {
        return collect(self::DATASET);
    }
}
