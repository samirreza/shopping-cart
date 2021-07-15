<?php

namespace App\Repositories\Product;

use App\Models\Product;

class ProductRepository implements ProductRepositoryInterface
{
    public function __construct(
        private Product $product
    )
    {}

    public function find(int $id): ?Product
    {
        return $this->product->find($id);
    }

    public function save(Product $product): void
    {
        $product->save();
    }
}
