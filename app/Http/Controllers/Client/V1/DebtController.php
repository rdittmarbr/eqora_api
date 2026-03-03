<?php

namespace App\Http\Controllers\Client\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DebtController extends BaseApiController
{
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'cpf' => ['nullable', 'digits_between:11,14'],
            'cnpj' => ['nullable', 'digits_between:14,14'],
        ]);

        $debts = [
            [
                'id' => 'deb_001',
                'description' => 'IPTU 2024',
                'due_date' => '2024-02-10',
                'amount' => 350.75,
                'currency' => 'BRL',
                'status' => 'open',
            ],
            [
                'id' => 'deb_002',
                'description' => 'Taxa de coleta de lixo',
                'due_date' => '2024-03-05',
                'amount' => 120.00,
                'currency' => 'BRL',
                'status' => 'open',
            ],
        ];

        return $this->success([
            'filters' => $request->only(['cpf', 'cnpj']),
            'data' => $debts,
        ]);
    }
}
