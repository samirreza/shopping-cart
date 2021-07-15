<?php

namespace App\Services;

use App\Models\Product;
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
        $product = Product::createFromRaw(
            $storeProductCommand->getName(),
            $storeProductCommand->getPrice()
        );

        $this->productRepository->save($product);

        return $product;
    }

    public function updateProduct(Product $product, array $changedAttributes): Product
    {
        $product->fill($changedAttributes);

        $this->productRepository->save($product);

        return $product;
    }
}
