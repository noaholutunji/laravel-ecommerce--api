<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Data\Repositories\Product\ProductRepository;
use App\Data\Repositories\Product\EloquentRepository as ProductEloquent;
use App\Data\Repositories\User\UserRepository;
use App\Data\Repositories\User\EloquentRepository as UserEloquent;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind( UserRepository::class, UserEloquent::class );
        $this->app->bind( ProductRepository::class, ProductEloquent::class );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
