<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Product extends Model
{
    // protected $table = 'products';

    // protected $with = ['user'];

    // protected $fillable = [
    //     'user_id',
    //     'name',
    //     'brand',
    //     'price',
    //     'image',
    //     'description'
    // ];

    protected $guarded = [];

    public function path()
    {
        return "/api/products/{$this->id}";
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // protected static function booted()
    // {
    //     static::creating(function ($product) {
    //         $product->user_id = Auth::id();
    //     });
    // }
}
