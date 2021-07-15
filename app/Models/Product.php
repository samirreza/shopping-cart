<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price'];

    public function offers()
    {
        return $this->hasMany(Offer::class);
    }

    public static function createFromRaw(string $name, int $price): self
    {
        $product = new self();
        $product->name = $name;
        $product->price = $price;

        return $product;
    }
}
