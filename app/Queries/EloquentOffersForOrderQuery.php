<?php

namespace App\Queries;

use App\DTO\Order;
use App\Models\Offer;

class EloquentOffersForOrderQuery implements OffersForOrderQueryInterface
{
    public function execute(Order $order): array
    {
        $query = Offer::query();
        foreach ($order->getOrderItems() as $orderItem) {
            $query->orWhere(function ($query) use ($orderItem) {
                $query
                    ->where('product_id', $orderItem->getProductId())
                    ->where('products_number', '<=', $orderItem->getProductCount());
            });
        }

        $offers = [];
        foreach ($query->get() as $offer) {
            $offers[$offer->product_id][$offer->products_number] = $offer;
        }

        return $offers;
    }
}
