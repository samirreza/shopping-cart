<?php

namespace App\Repositories\Product;

use App\Models\Product;

interface ProductRepositoryInterface
{
    public function find(int $id): ?Product;

    public function save(Product $product): void;
}
