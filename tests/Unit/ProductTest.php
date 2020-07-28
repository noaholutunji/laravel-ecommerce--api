<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_product_has_a_path()
    {
        $product = make('App\Product');

        $this->assertEquals("/api/products/{$product->id}", $product->path());
    }

    /** @test */
    public function a_product_has_an_owner()
    {
        $product = create('App\Product');

        $this->assertInstanceOf('App\User', $product->user);
    }
}
