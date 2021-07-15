<?php

namespace App\DTO;

class OrderItem
{
    private int $discount = 0;
    private array $appliedOffers = [];

    public function __construct(
        private int $productId,
        private string $productName,
        private int $productPrice,
        private int $productCount
    )
    {}

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getProductName(): string
    {
        return $this->productName;
    }

    public function getProductPrice(): int
    {
        return $this->productPrice;
    }

    public function getProductCount(): int
    {
        return $this->productCount;
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

    public function setAppliedOffers(array $appliedOffers): self
    {
        $this->appliedOffers = $appliedOffers;

        return $this;
    }

    public function getAppliedOffers(): array
    {
        return $this->appliedOffers;
    }
}
