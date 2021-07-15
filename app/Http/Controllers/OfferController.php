<?php

namespace App\Http\Controllers;

use App\DTO\OfferDTO;
use App\Models\Product;
use Illuminate\Http\Response;
use App\Services\OfferService;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\OfferResource;
use App\Http\Requests\StoreOfferRequest;
use App\Commands\SetProductOffersCommand;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class OfferController
{
    public function __construct(
        private OfferService $offerService
    )
    {}

    public function store(Product $product, StoreOfferRequest $storeOfferRequest): JsonResponse
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

    public function index(Product $product): AnonymousResourceCollection
    {
        $offers = $this->offerService->getProductOffers($product);

        return OfferResource::collection($offers);
    }

    public function delete(Product $product): Response
    {
        $this->offerService->deleteProductOffers($product);

        return response()->noContent()->setStatusCode(Response::HTTP_NO_CONTENT);
    }
}
