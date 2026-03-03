<?php

use App\Http\Controllers\Admin\V1\CityHallCallbackController;
use App\Http\Controllers\Admin\V1\DebtController;
use App\Http\Controllers\Admin\V1\EntityController;
use App\Http\Controllers\Admin\V1\EntityLoginEventController;
use App\Http\Controllers\Admin\V1\EntityTypeController;
use App\Http\Controllers\Admin\V1\FinancialPendencyController;
use App\Http\Controllers\Admin\V1\GatewayWebhookController;
use App\Http\Controllers\Admin\V1\LogController;
use App\Http\Controllers\Admin\V1\OperationStatusController;
use App\Http\Controllers\Admin\V1\PaymentController;
use App\Http\Controllers\Admin\V1\PartnerController;
use App\Http\Controllers\Admin\V1\PartnerTypeController;
use App\Http\Controllers\Admin\V1\PropertyController;
use App\Http\Controllers\Admin\V1\SimulationController;
use App\Http\Controllers\Admin\V1\TenantController;
use App\Http\Controllers\Admin\V1\TerminalController;
use Illuminate\Support\Facades\Route;

    Route::prefix('entities')->name('entities.')->group(function () {
        Route::get('/', [EntityController::class, 'index'])
            ->name('index');

        Route::post('/', [EntityController::class, 'store'])
            ->name('store');

        Route::get('/lookup/{document}', [EntityController::class, 'show'])
            ->whereNumber('document')
            ->name('lookup');

        Route::get('/{entity}', [EntityController::class, 'showById'])
            ->name('show');

        Route::put('/{entity}', [EntityController::class, 'update'])
            ->name('update');

        Route::patch('/{entity}', [EntityController::class, 'update'])
            ->name('patch');

        Route::delete('/{entity}', [EntityController::class, 'destroy'])
            ->name('destroy');

        Route::get('/{entity}/login-events', [EntityLoginEventController::class, 'index'])
            ->name('login-events.index');

        Route::post('/{entity}/login-events', [EntityLoginEventController::class, 'store'])
            ->name('login-events.store');
    });

    Route::prefix('entity-types')->name('entity-types.')->group(function () {
        Route::get('/', [EntityTypeController::class, 'index'])
            ->name('index');

        Route::post('/', [EntityTypeController::class, 'store'])
            ->name('store');

        Route::get('/{entityType}', [EntityTypeController::class, 'show'])
            ->name('show');

        Route::put('/{entityType}', [EntityTypeController::class, 'update'])
            ->name('update');

        Route::patch('/{entityType}', [EntityTypeController::class, 'update'])
            ->name('patch');

        Route::delete('/{entityType}', [EntityTypeController::class, 'destroy'])
            ->name('destroy');
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

    Route::prefix('partners')->name('partners.')->group(function () {
        Route::get('/', [PartnerController::class, 'index'])
            ->name('index');

        Route::post('/', [PartnerController::class, 'store'])
            ->name('store');

        Route::get('/{partner}', [PartnerController::class, 'show'])
            ->name('show');

        Route::put('/{partner}', [PartnerController::class, 'update'])
            ->name('update');

        Route::delete('/{partner}', [PartnerController::class, 'destroy'])
            ->name('destroy');

        Route::get('/{partner}/tenants', [TenantController::class, 'index'])
            ->name('tenants.index');

        Route::post('/{partner}/tenants', [TenantController::class, 'store'])
            ->name('tenants.store');
    });

    Route::prefix('partner-types')->name('partner-types.')->group(function () {
        Route::get('/', [PartnerTypeController::class, 'index'])
            ->name('index');

        Route::post('/', [PartnerTypeController::class, 'store'])
            ->name('store');

        Route::get('/{partnerType}', [PartnerTypeController::class, 'show'])
            ->name('show');

        Route::put('/{partnerType}', [PartnerTypeController::class, 'update'])
            ->name('update');

        Route::patch('/{partnerType}', [PartnerTypeController::class, 'update'])
            ->name('patch');

        Route::delete('/{partnerType}', [PartnerTypeController::class, 'destroy'])
            ->name('destroy');
    });

    Route::prefix('tenants')->name('tenants.')->group(function () {
        Route::get('/', [TenantController::class, 'index'])
            ->name('index');

        Route::post('/', [TenantController::class, 'store'])
            ->name('store');

        Route::get('/{tenant}', [TenantController::class, 'show'])
            ->name('show');

        Route::put('/{tenant}', [TenantController::class, 'update'])
            ->name('update');

        Route::patch('/{tenant}', [TenantController::class, 'update'])
            ->name('patch');

        Route::delete('/{tenant}', [TenantController::class, 'destroy'])
            ->name('destroy');

        Route::get('/{tenant}/terminals', [TerminalController::class, 'index'])
            ->name('terminals.index');

        Route::post('/{tenant}/terminals', [TerminalController::class, 'store'])
            ->name('terminals.store');

        Route::get('/{tenant}/terminals/{terminal}', [TerminalController::class, 'show'])
            ->name('terminals.show');

        Route::put('/{tenant}/terminals/{terminal}', [TerminalController::class, 'update'])
            ->name('terminals.update');

        Route::patch('/{tenant}/terminals/{terminal}', [TerminalController::class, 'update'])
            ->name('terminals.patch');

        Route::patch('/{tenant}/terminals/{terminal}/heartbeat', [TerminalController::class, 'heartbeat'])
            ->name('terminals.heartbeat');

        Route::delete('/{tenant}/terminals/{terminal}', [TerminalController::class, 'destroy'])
            ->name('terminals.destroy');

        Route::get('/{tenant}/terminals/{terminal}/login-events', [EntityLoginEventController::class, 'index'])
            ->name('terminals.login-events.index');

        Route::get('/{tenant}/entities', [EntityController::class, 'index'])
            ->name('entities.index');

        Route::post('/{tenant}/entities', [EntityController::class, 'store'])
            ->name('entities.store');

        Route::get('/{tenant}/entities/{entity}', [EntityController::class, 'showById'])
            ->name('entities.show');

        Route::put('/{tenant}/entities/{entity}', [EntityController::class, 'update'])
            ->name('entities.update');

        Route::patch('/{tenant}/entities/{entity}', [EntityController::class, 'update'])
            ->name('entities.patch');

        Route::delete('/{tenant}/entities/{entity}', [EntityController::class, 'destroy'])
            ->name('entities.destroy');

        Route::get('/{tenant}/entities/{entity}/login-events', [EntityLoginEventController::class, 'index'])
            ->name('entities.login-events.index');

        Route::post('/{tenant}/entities/{entity}/login-events', [EntityLoginEventController::class, 'store'])
            ->name('entities.login-events.store');

        Route::get('/{tenant}/entity-login-events', [EntityLoginEventController::class, 'index'])
            ->name('entity-login-events.index');

        Route::post('/{tenant}/entity-login-events', [EntityLoginEventController::class, 'store'])
            ->name('entity-login-events.store');
    });

    Route::prefix('terminals')->name('terminals.')->group(function () {
        Route::get('/', [TerminalController::class, 'index'])
            ->name('index');

        Route::post('/', [TerminalController::class, 'store'])
            ->name('store');

        Route::get('/{terminal}', [TerminalController::class, 'show'])
            ->name('show');

        Route::put('/{terminal}', [TerminalController::class, 'update'])
            ->name('update');

        Route::patch('/{terminal}', [TerminalController::class, 'update'])
            ->name('patch');

        Route::patch('/{terminal}/heartbeat', [TerminalController::class, 'heartbeat'])
            ->name('heartbeat');

        Route::delete('/{terminal}', [TerminalController::class, 'destroy'])
            ->name('destroy');

        Route::get('/{terminal}/login-events', [EntityLoginEventController::class, 'index'])
            ->name('login-events.index');
    });

    Route::prefix('entity-login-events')->name('entity-login-events.')->group(function () {
        Route::get('/', [EntityLoginEventController::class, 'index'])
            ->name('index');

        Route::post('/', [EntityLoginEventController::class, 'store'])
            ->name('store');

        Route::get('/{event}', [EntityLoginEventController::class, 'show'])
            ->name('show');
    });
