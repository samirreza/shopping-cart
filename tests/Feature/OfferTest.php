<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OfferTest extends TestCase
{
    use RefreshDatabase;

    public function test_set_product_offers_success()
    {
        $product = Product::create(['name' => 'test product', 'price' => 1000]);
        $response = $this->postJson("/api/products/$product->id/offers", ['offers' => [
            [
                'products_number' => 3,
                'price' => 2700,
            ],
            [
                'products_number' => 5,
                'price' => 4500,
            ],
            [
                'products_number' => 7,
                'price' => 6200,
            ]
        ]]);

        $response->assertStatus(201)->assertJson(function (AssertableJson $json) {
            $json->where('data.0.products_number', 3)
                ->where('data.0.price', 2700)
                ->where('data.1.products_number', 5)
                ->where('data.1.price', 4500)
                ->where('data.2.products_number', 7)
                ->where('data.2.price', 6200);
        });
    }

    public function test_set_product_offers_duplicate_number_error()
    {
        $product = Product::create(['name' => 'test product', 'price' => 1000]);
        $response = $this->postJson("/api/products/$product->id/offers", ['offers' => [
            [
                'products_number' => 5,
                'price' => 4500,
            ],
            [
                'products_number' => 5,
                'price' => 4400,
            ],
        ]]);

        self::assertArrayHasKey('offers', $response['errors']);
    }

    public function test_set_product_offers_validation_error()
    {
        $product = Product::create(['name' => 'test product', 'price' => 1000]);
        $response = $this->postJson("/api/products/$product->id/offers", ['offers' => [
            [
                'products_number' => 5,
            ],
            [
                'price' => 4400,
            ],
        ]]);

        self::assertArrayHasKey('offers.0.price', $response['errors']);
        self::assertArrayHasKey('offers.1.products_number', $response['errors']);
    }

    public function test_delete_product_offers()
    {
        $product = Product::create(['name' => 'test product', 'price' => 1000]);
        $response = $this->deleteJson("/api/products/$product->id/offers");
        $response->assertStatus(204);
        $this->assertDatabaseMissing('product_offers', ['product_id' => $product->id]);
    }

    public function test_get_product_offers()
    {
        $this->seed();
        $product = Product::first();
        $response = $this->getJson("/api/products/$product->id/offers");
        $response->assertStatus(200)->assertJson(function (AssertableJson $json) use ($product) {
            $json->where('data.0.product_id', $product->id);
            $json->has('data.0.products_number');
            $json->has('data.0.price');
        });
    }
}
