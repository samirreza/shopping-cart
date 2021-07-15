<?php

namespace App\DTO;

class Order
{
    private array $orderItems = [];

    public function addOrderItem(OrderItem $orderItem): void
    {
        $this->orderItems[] = $orderItem;
    }

    public function getOrderItems(): array
    {
        return $this->orderItems;
    }
}
