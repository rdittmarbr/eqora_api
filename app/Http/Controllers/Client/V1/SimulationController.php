<?php

namespace App\Http\Controllers\Client\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SimulationController extends BaseApiController
{
    public function simulate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'debts' => ['required', 'array', 'min:1'],
            'debts.*' => ['string'],
            'parcelas' => ['required', 'integer', ' between:1,18'],
        ]);

        $subtotal = 1000.00;
        $fees = 85.50;
        $total = $subtotal + $fees;

        return $this->success([
            'request' => $validated,
            'result' => [
                'subtotal' => $subtotal,
                'fees' => $fees,
                'total' => $total,
                'parcelas' => $validated['parcelas'],
                'valor_parcela' => round($total / $validated['parcelas'], 2),
                'regras' => [
                    'max_parcelas' => 18,
                    'tabela_taxas' => 'versao_2025_11',
                ],
            ],
        ]);
    }
}
