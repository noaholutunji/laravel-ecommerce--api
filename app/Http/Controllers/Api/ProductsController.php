<?php

namespace App\Http\Controllers\Api;

use App\Product;
use Illuminate\Http\Request;
use App\Data\Repositories\Product\ProductRepository;
use App\Http\Controllers\Controller;

class ProductsController extends Controller
{
    protected $product_repository;

    public function __construct(ProductRepository $product_repository)
	{
        $this->middleware('auth:api')->except(['index', 'show']);

		$this->product_repository = $product_repository;
	}
    public function index()
    {
        $products = $this->product_repository->getAllProducts();

        return response()->json([
            'products' => $products,
        ], 200);
    }

    public function store()
    {
        $attributes = request()->validate([
            'name' => 'required|string',
            'brand' => 'required|string',
            'price' => 'required|numeric',
            'image' => 'required|string',
            'description' => 'required|string'
        ]);

        $attributes['user_id'] = auth()->id();

        $product = $this->product_repository->createProduct($attributes);

        return response()->json([
            'product' => $product,
        ], 201);
    }

    public function show(Product $product)
    {
        return response()->json([
            'product' => $product,
        ], 200);
    }

    public function update(Product $product)
    {
        $this->authorize('update', $product);

        $attributes = request()->validate([
            'name' => 'required|string',
            'brand' => 'required|string',
            'price' => 'required|numeric',
            'image' => 'required|string',
            'description' => 'required|string'
        ]);

        $this->product_repository->updateProduct($product, $attributes);

        return response()->json([
            'success' => true,
            'data' => $product,
            "message" => "Product updated successfully"
        ], 200);
    }

    public function destroy(Product $product)
    {
        $this->authorize('update', $product);

       $product = $this->product_repository->deleteProduct($product);

        return response()->json([
            'success' => true,
            "message" => "Product deleted successfully"
        ], 200);
    }
}
