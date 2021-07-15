<?php

namespace App\Repositories\Product;

use App\Models\Product;

class ProductRepository implements ProductRepositoryInterface
{
    public function save(Product $product): void
    {
        $product->save();
    }
}
