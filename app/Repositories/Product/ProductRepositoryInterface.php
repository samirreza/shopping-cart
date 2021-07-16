<?php

namespace App\Repositories\Product;

use App\Models\Product;
use Illuminate\Support\Collection;

interface ProductRepositoryInterface
{
    public function find(int $id): ?Product;

    public function save(Product $product): void;

    public function getByIds(array $ids): Collection;

    // public function create(array $attributes): Product;

    // public function update(int $id, array $changedAttributes): Product;
}
