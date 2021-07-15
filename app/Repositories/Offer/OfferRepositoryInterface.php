<?php

namespace App\Repositories\Offer;

use App\Models\Product;
use Illuminate\Support\Collection;

interface OfferRepositoryInterface
{
    public function deleteProductOffers(Product $product): void;

    public function saveProductOffers(Product $product, array $offers): void;

    public function getProductOffers(Product $product): Collection;
}
