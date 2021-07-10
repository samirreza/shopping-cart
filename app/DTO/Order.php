<?php

namespace App\DTO;

class Order
{
    private ?int $totalPrice = null;
    private int $discount = 0;

    public function __construct(
        private array $orderItems
    )
    {}

    public function getOrderItems()
    {
        return $this->orderItems;
    }

    public function addTotalPrice(int $totalPrice): self
    {
        $this->totalPrice += $totalPrice;

        return $this;
    }

    public function getTotalPrice(): ?int
    {
        return $this->totalPrice;
    }

    public function addDiscount(int $discount): self
    {
        $this->discount += $discount;

        return $this;
    }

    public function getDiscount(): int
    {
        return $this->discount;
    }
}
