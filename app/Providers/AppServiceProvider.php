<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\PartitionIntegerServiceInterface;
use App\Services\RecursivePartitionIntegerService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(PartitionIntegerServiceInterface::class, RecursivePartitionIntegerService::class);
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
