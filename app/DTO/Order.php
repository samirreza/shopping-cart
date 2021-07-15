<?php

namespace App\DTO;

class Order
{
    private int $totalPrice;

    public function __construct(
        private array $orderItems
    )
    {}

    public function getOrderItems(): array
    {
        return $this->orderItems;
    }

    public function setTotalPrice(int $totalPrice): self
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    public function getTotalPrice(): ?int
    {
        return $this->totalPrice;
    }
}
