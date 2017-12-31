<?php

namespace BahaaAlhagar\ShoppingCart;

use Illuminate\Support\Facades\Session;

class ShoppingCart
{

    public $items = null;
    public $totalQty;
    public $totalPrice;

    /**
     * Create a new Skeleton Instance
     */
    function __construct()
    {
        $oldCart = Session::has('cart') ? Session::get('cart') : null;

        if($oldCart)
        {
            $this->items = $oldCart->items;
            $this->totalQty = $oldCart->totalQty;
            $this->totalPrice = $oldCart->totalPrice;
        }
        
    }

    /**
     * Friendly welcome
     *
     * @param object $item item to add
     *
     * @return string Returns the phrase passed in
     */
    public function add($item)
    {
        $id = $item->id;

        // add the item properties
        $storedItem = ['qty' => 0, 'price' => $item->price, 'item' => $item];

        // check if the item exists in the cart before
        // and if it exists then get it from the cart
        if($this->items)
        {
            if(array_key_exists($id, $this->items))
            {
                $storedItem = $this->items[$id];
            }
        }

        // update the cart increase qty and price
        $storedItem['qty']++;
        $storedItem['price'] = $item->price * $storedItem['qty'];
        $this->items[$id] = $storedItem;
        $this->totalQty++;
        $this->totalPrice += $item->price;

        // add the Cart to the Session
        Session::put('cart', $this);

        // return the Cart if needed
        return $this;
    }
}
