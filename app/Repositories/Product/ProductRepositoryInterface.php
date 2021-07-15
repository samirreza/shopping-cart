<?php

namespace App\Repositories\Product;

use App\Models\Product;

interface ProductRepositoryInterface
{
    public function save(Product $product): void;
}
