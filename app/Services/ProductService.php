<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;
use App\Commands\StoreProductCommand;

class ProductService
{
    // TODO: use Repository design pattern
    public function storeProduct(StoreProductCommand $storeProductCommand): Product
    {
        $product = new Product();
        $product->name = $storeProductCommand->getName();
        $product->price = $storeProductCommand->getPrice();
        $product->save();

        return $product;
    }

    // TODO: use DTO instead of changedAttributes
    public function updateProduct(Product $product, array $changedAttributes): Product
    {
        $product->update($changedAttributes);

        return $product;
    }

    // TODO: use Repository design pattern
    public function getProductsByIds(array $ids): Collection
    {
        return Product::whereIn('id', $ids)->get()->keyBy('id');
    }
}
