<?php

namespace App\Http\Controllers\Shop;

use Cart;
use Session;
use App\Shop\Product;
use Illuminate\Http\Request;
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

        return view('Shop/shop', compact('products'));
    }

    public function addToCart(Request $request, Product $product, $qty = null)
    {
        $qty ? Cart::add($product, $qty) : Cart::add($product);

        return back();
    }

    public function reduceOneItem($id, Request $request)
    {
        Cart::reduceOneItem($id);

        return redirect()->route('product.shoppingCart');
    }

    public function RemovefromCart($id, Request $request)
    {
        Cart::remove($id);

        return redirect()->route('product.shoppingCart');
    }

    public function modify(Request $request, $id)
    {
        $this->validate($request, [
            'qty' => 'required|integer'
            ]);

        Cart::modify($id, $request->qty);

        return redirect()->route('product.shoppingCart');
    }

    public function shoppingCart()
    {
        $cart = Cart::getContent();

        return view('Shop/shoppingCart', compact('cart'));
    }
}
