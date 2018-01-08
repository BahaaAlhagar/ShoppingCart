<?php

namespace App\Http\Controllers\Shop;

use Cart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\validateCheckOutRequest;

class OrderController extends Controller
{
    public function __construct()
    {
    	$this->middleware('auth');
    }

    public function checkOut()
    {
    	$cart = Cart::getContent();

    	if(!$cart || !$cart->totalPrice)
    	{
    		return redirect()->route('product.shoppingCart');
    	}

    	return view('Shop/checkOut', compact('cart'));
    }

    public function payment(validateCheckOutRequest $request)
    {

        // your payment options goes here.

        $user = auth()->user();

        $cart = Cart::getContent();

        $order = $user->orders()->create([
            'name' => $request->name,
            'address' => $request->address,
            'totalQty' => $cart->totalQty,
            'totalPrice' => $cart->totalPrice,
            'cart' => json_encode($cart),
            'payment_method' => 'optional your payment method',
            'payment_id' => 'optional your payment id'
        ]);

        foreach($cart->items as $product){
            $order->products()->attach($product['item']['id'], ['qty' => $product['qty'], 'price' => $product['price'], 'options' =>json_encode($product['options'])]);
        }

        Cart::destroy();

        session()->flash('success', 'Thanks for pruchase. you will get your order items soon enough.');

        return redirect()->route('product.shop');
    }
    
}
