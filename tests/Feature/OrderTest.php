<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Offer;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_without_offer()
    {
        $this->insertTestProducts();
        $response = $this->postJson('/api/order', ['products' => [
            ['id' => 1000],
            ['id' => 1001],
            ['id' => 1002],
        ]]);

        self::assertEquals($response['data']['totalPrice'], 6000);
        self::assertEmpty($response['data']['products'][0]['appliedOffers']);
        self::assertEmpty($response['data']['products'][1]['appliedOffers']);
        self::assertEmpty($response['data']['products'][2]['appliedOffers']);
    }

    public function test_order_with_offers()
    {
        $this->insertTestProducts();
        $product4 = Product::create(['name' => 'product four', 'price' => 500]);
        $product4->offers()->save(Offer::make(['products_number' => 3, 'price' => 1200]));
        $product5 = Product::create(['name' => 'product five', 'price' => 1000]);
        $product5->offers()->save(Offer::make(['products_number' => 2, 'price' => 1800]));

        $response = $this->postJson('/api/order', ['products' => [
            ['id' => $product4->id],
            ['id' => $product4->id],
            ['id' => 1000],
            ['id' => $product4->id],
            ['id' => $product5->id],
            ['id' => 1002],
            ['id' => $product5->id],
            ['id' => 1002],
            ['id' => $product4->id],
        ]]);

        self::assertEquals($response['data']['totalPrice'], 10500);
    }

    public function test_order_with_complex_offers()
    {
        $product = Product::create(['name' => 'test product', 'price' => 50]);
        $product->offers()->saveMany([
            Offer::make(['products_number' => 2, 'price' => 91]),
            Offer::make(['products_number' => 3, 'price' => 130]),
        ]);

        $response = $this->postJson('/api/order', ['products' => [
            ['id' => $product->id],
            ['id' => $product->id],
            ['id' => $product->id],
            ['id' => $product->id],
        ]]);

        self::assertEquals($response['data']['totalPrice'], 180);
    }

    public function test_order_validation_error()
    {
        $this->insertTestProducts();
        $response = $this->postJson('/api/order', ['products' => [
            ['id' => 1000],
            ['id' => 1001],
            ['id' => 2000],
        ]]);

        self::assertArrayHasKey('products.2.id', $response['errors']);
    }

    private function insertTestProducts()
    {
        Product::insert([
            ['id' => 1000, 'name' => 'product1', 'price' => 1000],
            ['id' => 1001, 'name' => 'product2', 'price' => 2000],
            ['id' => 1002, 'name' => 'product3', 'price' => 3000],
        ]);
    }
}
