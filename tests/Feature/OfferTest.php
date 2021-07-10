<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use Illuminate\Testing\Fluent\AssertableJson;

class OfferTest extends TestCase
{
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
            $json->has('data.2')
                ->where('data.0.products_number', 3)
                ->where('data.1.price', 4500);
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

        $response->assertStatus(422)->assertJson(function (AssertableJson $json) {
            $json->has('errors.offers');
        });
    }

    public function test_delete_product_deals()
    {
        $product = Product::create(['name' => 'test product', 'price' => 1000]);
        $response = $this->deleteJson("/api/products/$product->id/offers");
        $response->assertStatus(204);
        $this->assertDatabaseMissing('product_offers', ['product_id' => $product->id]);
    }
}
