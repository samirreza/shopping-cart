<?php

namespace App\Http\Controllers;

use App\DTO\OfferDTO;
use App\Models\Product;
use App\Services\OfferService;
use App\Http\Resources\OfferResource;
use App\Http\Requests\StoreOfferRequest;
use App\Commands\SetProductOffersCommand;
use Symfony\Component\HttpFoundation\Response;

class OfferController
{
    public function __construct(
        private OfferService $offerService
    )
    {}

    public function store(Product $product, StoreOfferRequest $storeOfferRequest)
    {
        $offersData = $storeOfferRequest->validated();
        $offerDTOs = [];
        foreach ($offersData['offers'] as $offerData) {
            $offerDTOs[] = new OfferDTO(
                $offerData['products_number'],
                $offerData['price']
            );
        }

        $setProductOffersCommand = new SetProductOffersCommand($product, $offerDTOs);
        $offers = $this->offerService->setProductOffers($setProductOffersCommand);

        return OfferResource::collection($offers)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function index(Product $product)
    {
        $offers = $this->offerService->getProductOffers($product);

        return OfferResource::collection($offers);
    }

    public function delete(Product $product)
    {
        $this->offerService->deleteProductOffers($product);

        return response()->noContent()->setStatusCode(Response::HTTP_NO_CONTENT);
    }
}
