<?php

namespace App\DTO;

use App\Models\Product;

class OrderItem
{
    private ?int $totalPrice = null;
    private array $appliedOffers = [];
    private int $discount = 0;

    public function __construct(
        private Product $product,
        private int $count
    )
    {}

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function setAppliedOffers(array $appliedOffers): self
    {
        $this->appliedOffers = $appliedOffers;

        return $this;
    }

    public function getAppliedOffers(): array
    {
        return $this->appliedOffers;
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

    public function setDiscount(int $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    public function getDiscount(): int
    {
        return $this->discount;
    }
}
