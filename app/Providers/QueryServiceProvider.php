<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Queries\EloquentOffersForOrderQuery;
use App\Queries\OffersForOrderQueryInterface;

class QueryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(OffersForOrderQueryInterface::class, EloquentOffersForOrderQuery::class);
    }
}
