<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'products' => $this->convertOrderItemsToArray($this->getOrderItems()),
            'totalPrice' => $this->getTotalPrice(),
        ];
    }

    private function convertOrderItemsToArray(array $orderItems): array
    {
        $result = [];
        foreach ($orderItems as $orderItem) {
            $result[] = [
                'name' => $orderItem->getProductName(),
                'price' => $orderItem->getProductPrice(),
                'count' => $orderItem->getProductCount(),
                'discount' => $orderItem->getDiscount(),
                'appliedOffers' => $this->convertAppliedOfferstoArray($orderItem->getAppliedOffers()),
            ];
        }

        return $result;
    }

    private function convertAppliedOfferstoArray(array $appliedOffers): array
    {
        $result = [];
        foreach ($appliedOffers as $appliedOffer) {
            $result[] = [
                'productsToBuy' => $appliedOffer->getProductsNumber(),
                'priceAfterOffer' => $appliedOffer->getPriceAfterOffer(),
            ];
        }

        return $result;
    }
}
