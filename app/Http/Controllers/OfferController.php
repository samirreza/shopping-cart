<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\DTO\Offer as OfferDTO;
use App\Services\OfferService;
use App\Http\Resources\OfferResource;
use App\Http\Requests\StoreOfferRequest;
use App\Commands\SetProductOffersCommand;
use Symfony\Component\HttpFoundation\Response;

class OfferController
{
    private OfferService $offerService;

    public function __construct(OfferService $offerService)
    {
        $this->offerService = $offerService;
    }

    public function index(Product $product)
    {
        $offers = $this->offerService->getProductOffers($product);

        return OfferResource::collection($offers);
    }

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
        $this->offerService->setProductOffers($setProductOffersCommand);

        return OfferResource::collection($product->offers)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function delete(Product $product)
    {
        $this->offerService->deleteAllProductOffers($product);

        return response()->noContent()->setStatusCode(Response::HTTP_NO_CONTENT);
    }
}
