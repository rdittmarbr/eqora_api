<?php

namespace App\Http\Controllers\Admin\V1;

use App\Models\FinancialPendency;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FinancialPendencyController extends BaseApiController
{
    /**
     * List pending debts for a citizen or property.
     */
    public function index(Request $request): JsonResponse
    {
        $document = $request->query('document');
        $property = $request->query('property_registration');

        $pendings = FinancialPendency::filter($document, $property);

        if ($pendings->isEmpty()) {
            return $this->error('financial_pendencies_not_found', 'No financial pendencies were found for the supplied filters.', 404);
        }

        return $this->success([
            'filters' => [
                'document' => $document,
                'property_registration' => $property,
            ],
            'data' => $pendings,
            'meta' => [
                'count' => $pendings->count(),
                'generated_at' => now()->toIso8601String(),
            ],
        ]);
    }
}
