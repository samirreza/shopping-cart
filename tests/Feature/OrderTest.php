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
        $response = $this->postJson("/api/order", ['products' => [
            ['id' => 1000],
            ['id' => 1001],
            ['id' => 1002],
        ]]);

        self::assertEquals($response['totalPrice'], 6000);
        self::assertEmpty($response['items'][0]['appliedOffers']);
    }

    public function test_order_with_offer()
    {
        $this->insertTestProducts();
        $product4 = Product::create(['name' => 'product four', 'price' => 500]);
        $product4->offers()->save(Offer::make(['products_number' => 3, 'price' => 1200]));
        $product5 = Product::create(['name' => 'product five', 'price' => 1000]);
        $product5->offers()->save(Offer::make(['products_number' => 2, 'price' => 1800]));

        $response = $this->postJson("/api/order", ['products' => [
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

        self::assertEquals($response['totalPrice'], 10500);
        self::assertEquals($response['discount'], 500);
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
