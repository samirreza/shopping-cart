<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;
use App\Commands\StoreProductCommand;
use App\Repositories\Product\ProductRepositoryInterface;

class ProductService
{
    public function __construct(
        private ProductRepositoryInterface $productRepository
    )
    {}

    public function storeProduct(StoreProductCommand $storeProductCommand): Product
    {
        $product = new Product();
        $product->name = $storeProductCommand->getName();
        $product->price = $storeProductCommand->getPrice();

        $this->productRepository->save($product);

        return $product;
    }

    public function updateProduct(Product $product, array $changedAttributes): Product
    {
        $product->fill($changedAttributes);

        $this->productRepository->save($product);

        return $product;
    }

    // TODO: use Repository design pattern
    public function getProductsByIds(array $ids): Collection
    {
        return Product::whereIn('id', $ids)->get()->keyBy('id');
    }
}
