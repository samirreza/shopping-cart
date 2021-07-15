<?php

namespace App\Providers;

use App\Services\OfferDiscountMutator;
use Illuminate\Support\ServiceProvider;
use App\Services\DiscountMutatorManager;

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
