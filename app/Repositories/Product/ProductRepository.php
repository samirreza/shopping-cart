<?php

namespace App\Repositories\Product;

use App\Models\Product;
use Illuminate\Support\Collection;

class ProductRepository implements ProductRepositoryInterface
{
    public function save(Product $product): void
    {
        $product->save();
    }

    public function getByIds(array $ids): Collection
    {
        return Product::whereIn('id', $ids)->get();
    }
}
