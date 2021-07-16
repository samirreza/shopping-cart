<?php

namespace App\Repositories\Product;

use App\Models\Product;
use Illuminate\Support\Collection;
// use Illuminate\Database\RecordsNotFoundException;

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

    public function getByIds(array $ids): Collection
    {
        return Product::whereIn('id', $ids)->get();
    }

    // public function create(array $attributes): Product
    // {
    //     return $this->product->create($attributes);
    // }

    // public function update(int $id, array $changedAttributes): Product
    // {
    //     $product = $this->find($id);
    //     if (!$product) {
    //         // We have to use Domain exceptions
    //         throw new RecordsNotFoundException();
    //     }

    //     $product->update($changedAttributes);

    //     return $product;
    // }
}
