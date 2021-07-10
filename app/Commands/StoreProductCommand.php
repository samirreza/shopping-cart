<?php

namespace App\Commands;

class StoreProductCommand
{
    public function __construct(
        private string $name,
        private int $price
    )
    {}

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): int
    {
        return $this->price;
    }
}
