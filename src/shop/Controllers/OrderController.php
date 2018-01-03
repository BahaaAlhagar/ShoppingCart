<?php

namespace App\Http\Controllers\ShoppingCart;

use Cart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ShoppingCart\validateCheckOutRequest;

class OrderController extends Controller
{
    public function __construct()
    {
    	$this->middleware('auth');
    }

    public function checkOut()
    {
    	$cart = Cart::get();

    	if(!$cart || !$cart->totalPrice)
    	{
    		return redirect()->route('product.shoppingCart');
    	}

    	return view('ShoppingCart/checkOut', compact('cart'));
    }

    public function payment(validateCheckOutRequest $request)
    {

    }
    
}
