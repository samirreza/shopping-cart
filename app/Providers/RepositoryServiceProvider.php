<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Offer\OfferRepository;
use App\Repositories\Product\ProductRepository;
use App\Repositories\Offer\OfferRepositoryInterface;
use App\Repositories\Product\ProductRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(OfferRepositoryInterface::class, OfferRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
