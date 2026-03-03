<?php

namespace App\Http\Controllers\Client\V1;

use App\Models\PaymentRecord;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentController extends BaseApiController
{
    public function createIntent(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'debts' => ['required', 'array', 'min:1'],
            'parcelas' => ['required', 'integer', ' between:1,18'],
            'total' => ['required', 'numeric', 'min:0'],
            'idempotencyKey' => ['required', 'string'],
        ]);

        $intentId = Str::uuid()->toString();

        Log::info('Payment intent created', [
            'intent_id' => $intentId,
            'idempotency_key' => $payload['idempotencyKey'],
        ]);

        return $this->success([
            'data' => [
                'intent_id' => $intentId,
                'status' => 'created',
                'expires_at' => now()->addMinutes(15)->toIso8601String(),
            ],
        ], 201);
    }

    public function confirmIntent(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'intentId' => ['required', 'string'],
            'gatewayRef' => ['required', 'string'],
        ]);

        Log::info('Payment confirmed', $payload);

        return $this->success([
            'data' => [
                'intent_id' => $payload['intentId'],
                'status' => 'confirmed',
                'gateway_reference' => $payload['gatewayRef'],
                'outbox_event_id' => Str::uuid()->toString(),
            ],
        ]);
    }

    public function show(string $paymentId): JsonResponse
    {
        $record = PaymentRecord::find($paymentId);

        if (!$record) {
            return $this->error('payment_not_found', 'Payment was not found.', 404);
        }

        return $this->success(['data' => $record]);
    }

    public function receipt(string $paymentId): JsonResponse
    {
        $record = PaymentRecord::find($paymentId);

        if (!$record) {
            return $this->error('payment_not_found', 'Payment was not found.', 404);
        }

        return $this->success([
            'data' => [
                'payment_id' => $paymentId,
                'number' => 'RCT-' . strtoupper(Str::random(8)),
                'issued_at' => now()->toIso8601String(),
                'url' => url("/receipts/{$paymentId}.pdf"),
                'method' => $record['method_label'],
                'amount' => $record['amount'],
                'currency' => $record['currency'],
                'installments' => $record['installments'],
                'paid_at' => $record['paid_at'],
            ],
        ]);
    }
}
