<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\SimplePartitionIntegerService;
use App\Services\PartitionIntegerServiceInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(PartitionIntegerServiceInterface::class, SimplePartitionIntegerService::class);
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
