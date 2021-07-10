<?php

namespace App\DTO;

class AppliedOffer
{
    public function __construct(
        private int $productsNumber,
        private int $totalPriceAfterOffer,
    )
    {}

    public function getProductsNumber(): int
    {
        return $this->productsNumber;
    }

    public function getTotalPriceAfterOffer(): ?int
    {
        return $this->totalPriceAfterOffer;
    }
}
