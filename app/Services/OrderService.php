<?php

namespace App\Services;

use App\DTO\Order;
use App\DTO\OrderItem;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Services\DiscountMutators\DiscountMutatorManager;

class OrderService
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private DiscountMutatorManager $discountMutatorManager
    )
    {}

    public function order(array $orderedProductIds): Order
    {
        $order = $this->createOrder($orderedProductIds);
        $this->discountMutatorManager->mutate($order);
        $order->setTotalPrice($this->getTotalPrice($order));

        return $order;
    }

    public function getTotalPrice(Order $order): int
    {
        $totalPrice = 0;
        foreach ($order->getOrderItems() as $orderItem) {
            $totalPrice += $this->getOrderItemTotalPrice($orderItem);
        }

        return $totalPrice;
    }

    public function getOrderItemTotalPrice(OrderItem $orderItem): int
    {
        return ($orderItem->getProductPrice() * $orderItem->getProductCount()) - $orderItem->getDiscount();
    }

    private function createOrder(array $orderedProductIds): Order
    {
        $orderedProducts = $this->productRepository->getByIds($orderedProductIds);
        $productCountPerId = array_count_values($orderedProductIds);
        $orderItems = [];
        foreach ($orderedProducts as $orderedProduct) {
            $orderItems[] = new OrderItem(
                $orderedProduct->id,
                $orderedProduct->name,
                $orderedProduct->price,
                $productCountPerId[$orderedProduct->id]
            );
        }

        return new Order($orderItems);
    }
}
