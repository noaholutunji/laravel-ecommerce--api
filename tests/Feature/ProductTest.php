<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateProductsTest extends TestCase
{
    use RefreshDatabase;


    /** @test */
    function guests_may_not_create_a_product()
    {
        $this->withExceptionHandling();

        $this->post('/api/products')
            ->assertRedirect('/api/login');
    }

    /** @test */
    function a_user_can_create_a_product()
    {
        $this->withExceptionHandling();
        $user = $this->signIn();

        $attributes = [
            'name' => 'Samsung Gz',
            'brand' => 'Samsung',
            'image' => 'win.png',
            'price' => 847,
            'description' => 'This is awesome'
        ];

        $this->post('/api/products', $attributes, $user)
                ->assertStatus(201);

        $this->assertDatabaseHas('products', $attributes);
    }

    /** @test */
    function test_a_product_requires_a_name()
    {
        $this->publishProduct(['name' => ''])
            ->assertSessionHasErrors('name');
    }

    /** @test */
    function test_a_product_requires_a_brand()
    {
        $this->publishProduct(['brand' => ''])
            ->assertSessionHasErrors('brand');
    }

    /** @test */
    function test_a_product_requires_a_image()
    {
        $this->publishProduct(['image' => ''])
            ->assertSessionHasErrors('image');
    }

    /** @test */
    function test_a_product_requires_a_price()
    {
        $this->publishProduct(['price' => ''])
            ->assertSessionHasErrors('price');
    }

    /** @test */
    function test_a_product_requires_a_description()
    {
        $this->publishProduct(['description' => ''])
            ->assertSessionHasErrors('description');
    }

     /** @test */
     function a_user_and_guest_can_view_all_products()
     {
         $product1 = factory('App\Product')->create([
             'name' => 'Sony vio',
             'brand' => 'Sony',
             'image' => 'http//unsplash.com/helloworld',
             'price' => '2000',
             'description' => 'A phone you will love'
         ]);

         $product2 = factory('App\Product')->create([
             'name' => 'Sony vio',
             'brand' => 'Sony',
             'image' => 'http//unsplash.com/helloworld',
             'price' => '2000',
             'description' => 'A phone you will love'
         ]);

         $response = $this->json('GET', '/api/products', [])
             ->assertStatus(200)
             ->assertSee($product1->name, $product2->name);
     }

    /** @test */
    public function a_user_and_guest_view_a_single_product()
    {
        $product = create('App\Product');

        $response = $this->json('GET', '/api/products', [])
        ->assertStatus(200)
        ->assertSee($product->name);
    }

    /** @test */
    function unauthorized_users_may_not_update_product()
    {
        $this->withExceptionHandling();

        $header = $this->signIn();

        $product = create('App\Product',
            ['user_id' => create('App\User')->id]
        );

        $this->patch($product->path(), [], $header)
            ->assertStatus(403);
    }

    /** @test */
    function a_product_can_be_updated_by_its_owner()
    {
        $this->withExceptionHandling();
        $user = create('App\User');

        $token = $user->createAccessToken($user);
        $headers = ['Authorization' => "Bearer $token"];

        $product = create('App\Product',
            ['user_id' => $user->id]
        );

        $attributes = [
            'name' => 'Sony vio',
            'brand' => 'Sony',
            'image' => 'http//unsplash.com/helloworld',
            'price' => '2000',
            'description' => 'A phone you will love'
        ];

        $this->json('PATCH', $product->path(), $attributes, $headers)
            ->assertStatus(200);
    }

    /** @test */
    function unauthorized_users_may_not_delete_product()
    {
        $this->withExceptionHandling();

        $user = $this->signIn();

        $product = create('App\Product');

        $this->delete($product->path(), [], $user)
            ->assertStatus(403);
    }


    /** @test */
    function authorized_user_can_delete_product()
    {
        $this->withExceptionHandling();

        $user = create('App\User');

        $token = $user->createAccessToken();
        $header = ['Authorization' => "Bearer $token"];

        $product = make('App\Product', ['user_id' => $user->id]);

        $this->delete($product->path(), [], $header);

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }


    protected function publishProduct($overrides = [])
    {
        $header =  $this->withExceptionHandling()->signIn();

        $products = make('App\Product', $overrides);

        return $this->post('/api/products', $products->toArray(), $header);
    }
}
