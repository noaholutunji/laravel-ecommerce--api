<?php

namespace App\Data\Repositories\Product;

use App\Product;

class EloquentRepository implements ProductRepository
{
    public function getAllProducts()
    {
        return  Product::all();
    }

    public function createProduct($attributes)
    {
        return Product::create($attributes);
    }

    public function updateProduct($product, $attributes)
    {
        return $product->update($attributes);
    }

    public function deleteProduct($product)
    {
        return $product->delete();
    }

}
