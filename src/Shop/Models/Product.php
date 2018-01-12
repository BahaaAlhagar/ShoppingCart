<?php

namespace App\Shop;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = ['id'];

    public function orders()
    {
    	return $this->belongsToMany(Product::class)->withPivot('qty', 'price', 'options');
    }
}
