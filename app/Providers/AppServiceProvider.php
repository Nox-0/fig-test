<?php

namespace App\Providers;

use App\Services\FertiliserInventoryService;
use App\Services\FertiliserInventoryServiceInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(FertiliserInventoryServiceInterface::class, FertiliserInventoryService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
