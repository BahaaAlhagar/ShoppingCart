<?php

namespace App\Http\Controllers\ShoppingCart;

use Cart;
use Session;
use Illuminate\Http\Request;
use App\ShoppingCart\Product;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::latest()->paginate(12);

        return view('ShoppingCart/shop', compact('products'));
    }


    public function addToCart(Request $request, Product $product)
    {
        Cart::add($product);

        return back();
    }

    public function reduceOneItem(Product $product, Request $request)
    {
        Cart::reduceOneItem($product);
        return back();
    }

    public function RemovefromCart(Product $product, Request $request)
    {
        Cart::remove($product);

        return back();
    }

    public function shoppingCart()
    {
        $cart = Cart::get();

        return view('ShoppingCart/shoppingCart', compact('cart'));
    }


}
