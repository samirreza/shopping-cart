<?php

namespace Database\Seeders;

use App\Models\Offer;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	for ($i=0; $i < 20; $i++) {
	        $product = Product::factory()->create();
	        $offer = Offer::factory()->for($product)->make();
	        $offer->price = ($offer->products_number * $product->price) - rand(1000, 1500);
	        $offer->save();
    	}
    }
}
