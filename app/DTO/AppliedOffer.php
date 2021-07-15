<?php

namespace App\DTO;

class AppliedOffer
{
    public function __construct(
        private int $productsNumber,
        private int $priceAfterOffer,
    )
    {}

    public function getProductsNumber(): int
    {
        return $this->productsNumber;
    }

    public function getPriceAfterOffer(): int
    {
        return $this->priceAfterOffer;
    }
}
