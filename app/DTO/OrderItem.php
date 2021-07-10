<?php

namespace App\DTO;

use App\Models\Product;

class OrderItem
{
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
}
