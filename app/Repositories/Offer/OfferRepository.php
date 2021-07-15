<?php

namespace App\Repositories\Offer;

use App\Models\Offer;
use App\Models\Product;
use Illuminate\Support\Collection;

class OfferRepository implements OfferRepositoryInterface
{
    public function deleteProductOffers(Product $product): void
    {
        Offer::where('product_id', $product->id)->delete();
    }

    public function saveProductOffers(Product $product, array $offers): void
    {
        $product->offers()->saveMany($offers);
    }

    public function getProductOffers(Product $product): Collection
    {
        return $product->offers;
    }
}
