<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\ProductService;
use App\Commands\StoreProductCommand;
use App\Http\Resources\ProductResource;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductController
{
    public function __construct(
        private ProductService $productService
    )
    {}

    public function store(StoreProductRequest $storeProductRequest): ProductResource
    {
        $productData = $storeProductRequest->validated();
        $storeProductCommand = new StoreProductCommand(
            $productData['name'],
            $productData['price']
        );
        $product = $this->productService->storeProduct($storeProductCommand);

        return ProductResource::make($product);
    }

    public function update(Product $product, UpdateProductRequest $updateProductRequest): ProductResource
    {
        $validatedData = $updateProductRequest->validated();
        $product = $this->productService->updateProduct($product, $validatedData);

        return ProductResource::make($product);
    }

    public function show(Product $product): ProductResource
    {
        return ProductResource::make($product);
    }
}
