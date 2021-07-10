<?php

namespace App\Services;

use App\Models\Offer;
use App\Models\Product;
use Illuminate\Support\Collection;
use App\Commands\SetProductOffersCommand;

class OfferService
{
    public function getProductOffers(Product $product): Collection
    {
        return $product->offers;
    }

    public function setProductOffers(SetProductOffersCommand $setProductOffersCommand)
    {
        $product = $setProductOffersCommand->getProduct();
        $this->deleteAllProductOffers($product);
        $offers = [];
        foreach ($setProductOffersCommand->getOfferDTOs() as $offerDTO) {
            $offer = new Offer();
            $offer->products_number = $offerDTO->getProductsNumber();
            $offer->price = $offerDTO->getPrice();
            $offers[] = $offer;
        }

        $product->offers()->saveMany($offers);
    }

    public function deleteAllProductOffers(Product $product)
    {
        Offer::where('product_id', $product->id)->delete();
    }
}
