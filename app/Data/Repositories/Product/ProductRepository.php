<?php

namespace App\Data\Repositories\Product;
use Illuminate\Http\Request;

interface ProductRepository
{
    public function getAllProducts();

    public function createProduct($attributes);

    public function updateProduct($product, $attributes);

    public function deleteProduct($product);

}
