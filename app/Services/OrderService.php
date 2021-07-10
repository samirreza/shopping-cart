<?php

namespace App\Services;

use App\DTO\Order;
use App\DTO\OrderItem;

class OrderService
{
    public function __construct(
        private ProductService $productService,
        private OrderPriceCalculatorServiceBasedOnOffers $orderPriceCalculatorServiceBasedOnOffers
    )
    {}

    public function order(array $productIds): Order
    {
        $orderItems = $this->createOrderItems($productIds);
        $order = new Order($orderItems);
        $this->orderPriceCalculatorServiceBasedOnOffers->calculate($order);

        return $order;
    }

    private function createOrderItems(array $productIds)
    {
        $mapping = [];
        foreach ($productIds as $productId) {
            if (isset($mapping[$productId])) {
                $mapping[$productId] = $mapping[$productId] + 1;
            } else {
                $mapping[$productId] = 1;
            }
        }

        $products = $this->productService->getProductsByIds($productIds);

        $orderItems = [];
        foreach ($mapping as $productId => $count) {
            $orderItems[] = new OrderItem(
                $products[$productId],
                $count
            );
        }

        return $orderItems;
    }
}
