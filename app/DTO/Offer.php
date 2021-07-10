<?php

namespace App\DTO;

class Offer
{
    public function __construct(
        private int $productsNumber,
        private int $price
    )
    {}

    public function getProductsNumber(): int
    {
        return $this->productsNumber;
    }

    public function getPrice(): int
    {
        return $this->price;
    }
}
