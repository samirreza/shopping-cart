<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Offer extends Model
{
    use HasFactory;

    protected $table = 'product_offers';

    protected $fillable = ['products_number', 'price'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public static function createFromRaw(int $productsNumber, int $price): self
    {
        $offer = new self();
        $offer->products_number = $productsNumber;
        $offer->price = $price;

        return $offer;
    }
}
