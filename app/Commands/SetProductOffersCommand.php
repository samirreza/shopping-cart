<?php

namespace App\Commands;

use App\Models\Product;

class SetProductOffersCommand
{
    public function __construct(
        private Product $product,
        private array $offerDTOs
    )
    {}

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getOfferDTOs(): array
    {
        return $this->offerDTOs;
    }
}
