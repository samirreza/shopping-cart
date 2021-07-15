<?php

namespace App\Services;

use App\Models\Offer;
use App\Models\Product;
use Illuminate\Support\Collection;
use App\Commands\SetProductOffersCommand;
use App\Repositories\Offer\OfferRepositoryInterface;

class OfferService
{
    public function __construct(
        private OfferRepositoryInterface $offerRepository
    )
    {}

    public function setProductOffers(SetProductOffersCommand $setProductOffersCommand): array
    {
        $product = $setProductOffersCommand->getProduct();
        $this->offerRepository->deleteProductOffers($product);
        $offers = [];
        foreach ($setProductOffersCommand->getOfferDTOs() as $offerDTO) {
            $offers[] = Offer::createFromRaw(
                $offerDTO->getProductsNumber(),
                $offerDTO->getPrice()
            );
        }

        $this->offerRepository->saveProductOffers($product, $offers);

        return $offers;
    }

    public function getProductOffers(Product $product): Collection
    {
        return $this->offerRepository->getProductOffers($product);
    }

    public function deleteProductOffers(Product $product)
    {
        $this->offerRepository->deleteProductOffers($product);
    }
}
