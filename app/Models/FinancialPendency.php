<?php

namespace App\Models;

use Illuminate\Support\Collection;

class FinancialPendency
{
    private const DATASET = [
        [
            'id' => 'debt_001',
            'citizen_document' => '12345678901',
            'property_registration' => 'CUR-001-XYZ',
            'description' => 'IPTU 2024',
            'due_date' => '2024-02-10',
            'amount' => 350.75,
            'currency' => 'BRL',
            'status' => 'open',
        ],
        [
            'id' => 'debt_002',
            'citizen_document' => '12345678901',
            'property_registration' => 'CUR-001-XYZ',
            'description' => 'Taxa de coleta de lixo',
            'due_date' => '2024-03-05',
            'amount' => 120.00,
            'currency' => 'BRL',
            'status' => 'open',
        ],
        [
            'id' => 'debt_003',
            'citizen_document' => '98765432100',
            'property_registration' => 'CUR-002-ABC',
            'description' => 'Taxa de iluminação pública',
            'due_date' => '2024-04-15',
            'amount' => 80.50,
            'currency' => 'BRL',
            'status' => 'closed',
        ],
    ];

    public static function filter(?string $document, ?string $registration): Collection
    {
        $doc = $document ? preg_replace('/\D/', '', $document) : null;
        $reg = $registration ? strtoupper($registration) : null;

        return collect(self::DATASET)->filter(function ($item) use ($doc, $reg) {
            $matchesDocument = $doc ? $item['citizen_document'] === $doc : true;
            $matchesProperty = $reg ? strtoupper($item['property_registration']) === $reg : true;
            return $matchesDocument && $matchesProperty;
        })->values();
    }
}
