<?php

namespace App\DTO;

class Order
{
    public function __construct(
        private array $orderItems
    )
    {}

    public function getOrderItems()
    {
        return $this->orderItems;
    }
}
