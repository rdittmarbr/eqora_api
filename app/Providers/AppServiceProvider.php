<?php

namespace App\Providers;

use App\Modules\Device\Contracts\DeviceManager;
use App\Modules\Device\Services\DeviceManagerService;
use App\Modules\Entity\Contracts\EntityFinder;
use App\Modules\Entity\Services\EntityFinderService;
use App\Modules\Partner\Contracts\PartnerManager;
use App\Modules\Partner\Services\PartnerManagerService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(DeviceManager::class, DeviceManagerService::class);
        $this->app->bind(EntityFinder::class, EntityFinderService::class);
        $this->app->bind(PartnerManager::class, PartnerManagerService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
