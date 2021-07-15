<?php

namespace App\Repositories\Product;

use App\Models\Product;
use Illuminate\Support\Collection;

interface ProductRepositoryInterface
{
    public function save(Product $product): void;

    public function getByIds(array $ids): Collection;
}
