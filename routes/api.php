<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('login', 'Api\UserController@login')->name('login');;
Route::post('register', 'Api\UserController@register')->name('register');;

Route::group(['middleware' => 'auth:api'], function(){
    Route::post('logout', 'Api\UserController@logout');
});

Route::get('/products', 'Api\ProductsController@index');
Route::post('/products', 'Api\ProductsController@store');
Route::get('/products/{product}', 'Api\ProductsController@show');
Route::patch('/products/{product}', 'Api\ProductsController@update');
Route::delete('/products/{product}', 'Api\ProductsController@destroy');
