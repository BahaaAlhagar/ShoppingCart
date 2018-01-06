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
     * get the cart from the Session
     *
     * @return get the session cart array and the cart object or null
     */
    public function get()
    {
        // Get the Cart from the Session if there is one
        $cart = Session::has('cart') ? Session::get('cart') : null;

        // return the Cart
        return $cart;
    }

    /**
     * update the cart in the Session
     *
     * @return update the session cart array and the cart object 
     */
    public function update()
    {
        // add the Cart to the Session
        Session::put('cart', $this);

        // return the Cart if needed
        return $this;
    }

    /**
     * empty the cart in the Session
     *
     * @return empty the session cart array 
     */
    public function destroy()
    {
        // remove the Cart from the Session
        Session::forget('cart');
    }

    /**
     * Add 1 item to the Cart
     *
     * @param object $item item to add
     *
     * @return update the session cart array and the cart object 
     */
    public function add($item)
    {
        // we need id for the array index
        $id = $item->id;

        // add the item properties
        $storedItem = ['qty' => 0, 'price' => $item->price, 'item' => $item->toArray()];

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

        // add the cart to the Session
        $this->update();
    }

    /**
     * Reduce 1 item from the Cart
     *
     * @param object $item item to reduce by 1
     *
     * @return update the session cart array and the cart object 
     */
    public function reduceOneItem($item)
    {
        // we need id for the array index
        $id = $item->id;

        // decrease the qty and the price in the cart
        $this->items[$id]['qty']--;
        $this->items[$id]['price'] -= $this->items[$id]['item']['price'];
        $this->totalQty--;
        $this->totalPrice -= $this->items[$id]['item']['price'];

        // if the qty is 0 or less remove the item from Cart
        if($this->items[$id]['qty'] <= 0)
        {
            unset($this->items[$id]);
        }

        // update the Cart in the Session
        $this->update();
    }

    /**
     * Remove item from the Cart
     *
     * @param object $item item to remove
     *
     * @return update the session cart array and the cart object 
     */
    public function remove($item)
    {
        // we need id for the array index
        $id = $item->id;

        // remove item qty and price from cart
        $this->totalQty -= $this->items[$id]['qty'];
        $this->totalPrice -= $this->items[$id]['price'];
        unset($this->items[$id]);

        // update the Cart in the Session
        $this->update();
    }

    /**
     * get the cart items count
     *
     * @return the cart total quantity 
     */
    public function count()
    {
        // we need id for the array index
        return $this->totalQty ? $this->totalQty : null;
    }

}