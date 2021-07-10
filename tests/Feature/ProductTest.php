<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use Illuminate\Testing\Fluent\AssertableJson;

class ProductTest extends TestCase
{
    public function test_success_product_creation()
    {
        $response = $this->postJson('/api/products', [
            'name' => 'test product',
            'price' => 1000,
        ]);

        $response->assertStatus(201)->assertJson(function (AssertableJson $json) {
            $json->has('data.id');
            $json->where('data.name', 'test product');
            $json->where('data.price', 1000);
        });
    }

    public function test_faild_product_creation_for_missing_name()
    {
        $response = $this->postJson('/api/products', ['price' => 1000]);

        $response->assertStatus(422)->assertJson(function (AssertableJson $json) {
            $json->has('errors.name');
        });
    }

    public function test_faild_product_creation_for_missing_price()
    {
        $response = $this->postJson('/api/products', ['name' => 'test product']);

        $response->assertStatus(422)->assertJson(function (AssertableJson $json) {
            $json->has('errors.price');
        });
    }

    public function test_success_product_update()
    {
        $product = Product::create(['name' => 'old product', 'price' => 1000]);
        $response = $this->putJson("/api/products/$product->id", [
            'name' => 'new product',
            'price' => 2000,
        ]);

        $response->assertStatus(200)->assertJson(function (AssertableJson $json) {
            $json->has('data.id');
            $json->where('data.name', 'new product');
            $json->where('data.price', 2000);
        });
    }
}
