<?php

namespace App\Formatters;

use App\DTO\Order;
use App\Models\Product;

class OrderFormatter
{
    public function format(Order $order): array
    {
        return [
            'totalPrice' => $order->getTotalPrice(),
            'discount' => $order->getDiscount(),
            'items' => $this->formatOrderItems($order->getOrderItems()),
        ];
    }

    private function formatOrderItems(array $orderItems): array
    {
        $result = [];
        foreach ($orderItems as $orderItem) {
            $result[] = [
                'products' => $this->formatProducts($orderItem->getProduct()),
                'count' => $orderItem->getCount(),
                'totalPrice' => $orderItem->getTotalPrice(),
                'discount' => $orderItem->getDiscount(),
                'appliedOffers' => $this->formatAppliedOffers($orderItem->getAppliedOffers()),
            ];
        }

        return $result;
    }

    private function formatProducts(Product $product): array
    {
        return [
            'name' => $product->name,
            'price' => $product->price,
        ];
    }

    private function formatAppliedOffers(array $appliedOffers): array
    {
        $result = [];
        foreach ($appliedOffers as $appliedOffer) {
            $result[] = [
                'productsToBuy' => $appliedOffer->getProductsNumber(),
                'totalPriceAfterOffer' => $appliedOffer->getTotalPriceAfterOffer(),
            ];
        }

        return $result;
    }
}
