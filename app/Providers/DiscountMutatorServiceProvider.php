<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\DiscountMutators\OfferDiscountMutator;
use App\Services\DiscountMutators\DiscountMutatorManager;

class DiscountMutatorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->tag([OfferDiscountMutator::class], 'discountMutator');

        $this->app->bind(DiscountMutatorManager::class, function ($app) {
            return new DiscountMutatorManager(...$app->tagged('discountMutator'));
        });
    }
}
