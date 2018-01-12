<?php

namespace App\Shop;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = ['id', 'user_id'];

    protected $casts = [
    	'cart' => 'json'
    ];

    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    public function products()
    {
    	return $this->belongsToMany(Product::class)->withPivot('qty', 'price', 'options');
    }
}
