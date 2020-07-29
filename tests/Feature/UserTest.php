<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function guest_requires_a_firstname_to_register()
    {
        $this->registerUser(['firstname' => null])
            ->assertSessionHasErrors('firstname');
    }

    /** @test */
    function  guest_requires_a_lastname_to_register()
    {
        $this->registerUser(['lastname' => null])
            ->assertSessionHasErrors('lastname');
    }

    /** @test */
    function  guest_requires_a_phonenumber_to_register()
    {
        $this->registerUser(['phonenumber' => null])
            ->assertSessionHasErrors('phonenumber');
    }

    /** @test */
    function  guest_requires_an_email_to_register()
    {
        $this->registerUser(['email' => null])
            ->assertSessionHasErrors('email');
    }

    /** @test */
    function  guest_requires_a_password_to_register()
    {
        $this->registerUser(['password' => null])
            ->assertSessionHasErrors('password');
    }

    public function registerUser($overrides = [])
    {
        $this->withExceptionHandling();

        $user = make('App\User', $overrides);

        return $this->post('api/register', $user->toArray());
    }
   /** @test */
   public function a_user_can_login()
    {
       create('App\User', [
            'email' => 'osuolale49@gmail.com',
            'password' => bcrypt('olutunji91'),
        ]);

        $response = $this->json('POST', 'api/login', [
            'email' => 'osuolale49@gmail.com',
            'password' => 'fakePassword',
        ]);

        $response->assertStatus(401);

        $payload = ['email' => 'osuolale49@gmail.com', 'password' => 'olutunji91'];

        $this->json('POST', 'api/login', $payload)
            ->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'email' => 'osuolale49@gmail.com',
        ]);
    }

    /** @test */
    public function a_guest_can_register()
    {
        $this->withExceptionHandling();

        $payload = [
            'firstname' => 'Noah',
            'lastname' => 'Osuolale',
            'email' => 'osuolale49@gmail.com',
            'phonenumber' => '08162785303',
            'password' => 'olutunji91',
        ];

        $this->json('post', '/api/register', $payload)
            ->assertStatus(201);
    }

    /** @test */
    function a_user_can_logout()
    {
        $user = $this->signIn();

        $this->json('post', '/api/logout', [], $user)
            ->assertStatus(200);
    }
}
