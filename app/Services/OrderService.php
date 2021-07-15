<?php

namespace App\Services;

use App\DTO\Order;
use App\DTO\OrderItem;
use App\Repositories\Product\ProductRepositoryInterface;

class OrderService
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private DiscountMutatorManager $discountMutatorManager
    )
    {}

    public function order(array $orderedProductIds): Order
    {
        $order = new Order();
        $this->addOrderItemsToOrder($order, $orderedProductIds);
        $this->discountMutatorManager->mutate($order);

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

    private function addOrderItemsToOrder(Order $order, array $orderedProductIds): void
    {
        $orderedProducts = $this->productRepository->getByIds($orderedProductIds);
        $productCountPerId = array_count_values($orderedProductIds);
        foreach ($orderedProducts as $orderedProduct) {
            $order->addOrderItem(
                new OrderItem(
                    $orderedProduct->id,
                    $orderedProduct->name,
                    $orderedProduct->price,
                    $productCountPerId[$orderedProduct->id]
                )
            );
        }
    }
}
