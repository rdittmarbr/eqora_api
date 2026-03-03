<?php

use App\Http\Controllers\Client\V1\CityHallCallbackController;
use App\Http\Controllers\Client\V1\DebtController;
use App\Http\Controllers\Client\V1\EntityController;
use App\Http\Controllers\Client\V1\EntityLoginEventController;
use App\Http\Controllers\Client\V1\FinancialPendencyController;
use App\Http\Controllers\Client\V1\GatewayWebhookController;
use App\Http\Controllers\Client\V1\LogController;
use App\Http\Controllers\Client\V1\OperationStatusController;
use App\Http\Controllers\Client\V1\PaymentController;
use App\Http\Controllers\Client\V1\PropertyController;
use App\Http\Controllers\Client\V1\SimulationController;
use App\Http\Controllers\Client\V1\TerminalController;
use Illuminate\Support\Facades\Route;

    Route::prefix('entities')->name('entities.')->group(function () {
        Route::get('/{document}', [EntityController::class, 'show'])
            ->whereNumber('document')
            ->name('show');

        Route::get('/{entity}/login-events', [EntityLoginEventController::class, 'index'])
            ->name('login-events.index');

        Route::post('/{entity}/login-events', [EntityLoginEventController::class, 'store'])
            ->name('login-events.store');
    });

    Route::prefix('properties')->name('properties.')->group(function () {
        Route::get('/{registration}', [PropertyController::class, 'show'])
            ->name('show');
    });

    Route::prefix('financial-pendencies')->name('financial-pendencies.')->group(function () {
        Route::get('/', [FinancialPendencyController::class, 'index'])
            ->name('index');
    });

    Route::prefix('debts')->name('debts.')->group(function () {
        Route::get('/', [DebtController::class, 'index'])
            ->name('index');
    });

    Route::prefix('installments')->name('simulations.')->group(function () {
        Route::post('/simulate', [SimulationController::class, 'simulate'])
            ->name('store');
    });

    Route::prefix('payments')->name('payments.')->group(function () {
        Route::post('/intent', [PaymentController::class, 'createIntent'])
            ->name('intent');

        Route::post('/confirm', [PaymentController::class, 'confirmIntent'])
            ->name('confirm');

        Route::get('/{payment}', [PaymentController::class, 'show'])
            ->name('show');
    });

    Route::prefix('receipts')->name('payments.')->group(function () {
        Route::get('/{payment}', [PaymentController::class, 'receipt'])
            ->name('receipt');
    });

    Route::prefix('webhooks')->name('webhooks.')->group(function () {
        Route::post('/gateway', [GatewayWebhookController::class, 'handle'])
            ->name('gateway');
    });

    Route::prefix('integrations')->name('integrations.')->group(function () {
        Route::post('/city-hall/callback', [CityHallCallbackController::class, 'handle'])
            ->name('cityhall.callback');
    });

    Route::prefix('logs')->name('logs.')->group(function () {
        Route::get('/', [LogController::class, 'index'])
            ->name('index');
    });

    Route::prefix('operations')->name('operations.')->group(function () {
        Route::get('/{operation}/status', [OperationStatusController::class, 'show'])
            ->name('status');
    });

    Route::prefix('terminals')->name('terminals.')->group(function () {
        Route::get('/', [TerminalController::class, 'index'])
            ->name('index');

        Route::post('/', [TerminalController::class, 'store'])
            ->name('store');

        Route::get('/{terminal}', [TerminalController::class, 'show'])
            ->name('show');

        Route::patch('/{terminal}/heartbeat', [TerminalController::class, 'heartbeat'])
            ->name('heartbeat');
    });

require __DIR__ . '/devices.php';
