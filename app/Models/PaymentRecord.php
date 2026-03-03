<?php

namespace App\Models;

class PaymentRecord
{
    private const DATASET = [
        [
            'id' => 'pay_001',
            'intent_id' => 'intent_abc',
            'paid_at' => '2025-01-15T14:32:00Z',
            'method' => 'credit_card',
            'method_label' => 'Cartão de Crédito',
            'installments' => 12,
            'amount' => 1085.50,
            'currency' => 'BRL',
            'channel' => 'Totem',
            'status' => 'confirmed',
            'details' => [
                'card_last_digits' => '4321',
                'brand' => 'Visa',
                'holder' => 'João da Silva',
            ],
        ],
        [
            'id' => 'pay_002',
            'intent_id' => 'intent_def',
            'paid_at' => '2025-01-12T09:15:00Z',
            'method' => 'boleto',
            'method_label' => 'Boleto Bancário',
            'installments' => 1,
            'amount' => 420.00,
            'currency' => 'BRL',
            'channel' => 'Web',
            'status' => 'settled',
            'details' => [
                'linha_digitavel' => '23793.38127 60007.163907 64000.063209 1 90060000042000',
            ],
        ],
        [
            'id' => 'pay_003',
            'intent_id' => 'intent_xyz',
            'paid_at' => '2025-01-10T17:45:00Z',
            'method' => 'pix',
            'method_label' => 'Pix',
            'installments' => 1,
            'amount' => 250.75,
            'currency' => 'BRL',
            'channel' => 'Mobile',
            'status' => 'confirmed',
            'details' => [
                'pix_txid' => 'EKO-PIX-2025-01-10-174500',
            ],
        ],
    ];

    public static function find(string $paymentId): ?array
    {
        return collect(self::DATASET)->first(fn ($item) => $item['id'] === $paymentId);
    }
}
