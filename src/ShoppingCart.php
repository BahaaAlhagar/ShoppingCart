<?php

namespace BahaaAlhagar\ShoppingCart;

use Illuminate\Support\Facades\Session;
use BahaaAlhagar\ShoppingCart\Exceptions\CartIsEmptyException;
use BahaaAlhagar\ShoppingCart\Exceptions\NegativeQuantityException;
use BahaaAlhagar\ShoppingCart\Exceptions\UnknownUniqueIndexException;

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
    public function add($item, array $options = null)
    {
        // we need unique index for the item
        $uniqueIndex = $options ? $this->createUniqueIndex($item->id, $options) : $item->id;

        // add the item properties
        $storedItem = ['qty' => 0, 'price' => $item->price, 'options' => $options, 'item' => $item->toArray()];

        // check if the item exists in the cart before
        // and if it exists then get it from the cart
        if($this->items)
        {
            if(array_key_exists($uniqueIndex, $this->items))
            {
                $storedItem = $this->items[$uniqueIndex];
            }
        }

        // update the cart increase qty and price
        $storedItem['qty']++;
        $storedItem['price'] = $item->price * $storedItem['qty'];
        $this->items[$uniqueIndex] = $storedItem;
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
    public function reduceOneItem($uniqueIndex)
    {
        // decrease the qty and the price in the cart
        $this->items[$uniqueIndex]['qty']--;
        $this->items[$uniqueIndex]['price'] -= $this->items[$uniqueIndex]['item']['price'];
        $this->totalQty--;
        $this->totalPrice -= $this->items[$uniqueIndex]['item']['price'];

        // if the qty is 0 or less remove the item from Cart
        if($this->items[$uniqueIndex]['qty'] <= 0)
        {
            unset($this->items[$uniqueIndex]);
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
    public function remove($uniqueIndex)
    {
        // remove item qty and price from cart
        $this->totalQty -= $this->items[$uniqueIndex]['qty'];
        $this->totalPrice -= $this->items[$uniqueIndex]['price'];
        unset($this->items[$uniqueIndex]);

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

    /**
     * create unique index
     *
     * @param item
     *
     * @param options array
     *
     * @return uniqueIndex for the shopping cart 
     */
    public function createUniqueIndex($id, $options)
    {
        $uniqueIndex = $id;

        if($options){
            foreach($options as $key => $value){
                $uniqueIndex .= '_' .$key. '_' .$value;
            }
        }

        $uniqueIndex = base64_encode($uniqueIndex);
        return $uniqueIndex;
    }

    /**
     * update item in the Cart
     *
     * @param object $item item to add
     *
     * @return update the session cart array and the cart object 
     */
    public function modify($uniqueIndex, $qty)
    {
        // check quantity
        if($qty < 0)
        {
            throw new NegativeQuantityException('Negative Quantity!');
        }

        // check if the item exists in the cart before
        if(!$this->items){
            throw new CartIsEmptyException('Cart is empty');
        } 

        if(!array_key_exists($uniqueIndex, $this->items))
        {
            throw new UnknownUniqueIndexException("The cart does not contain this index {$uniqueIndex}.");
        }

        // get the current item from the cart
        $currentItem = $this->items[$uniqueIndex];

        // if its the same qty do nothing
        if($currentItem['qty'] == $qty)
        {
            return false;
        }

        $currentItemPrice = $this->getItemPrice($uniqueIndex);
        

        $this->totalPrice -= $currentItem['price'];
        $this->totalPrice += ($qty * $currentItemPrice);

        $this->totalQty -= $currentItem['qty'];
        $this->totalQty += $qty;

        $this->items[$uniqueIndex]['qty'] = $qty;

        $this->items[$uniqueIndex]['price'] = $qty * $currentItemPrice;

        // if the qty is 0 or less remove the item from Cart
        if($this->items[$uniqueIndex]['qty'] <= 0)
        {
            unset($this->items[$uniqueIndex]);
        }

        // add the cart to the Session
        $this->update();
    }

    /**
     * get item price
     *
     * @param unique index
     *
     * @return the item price
     */
    public function getItemPrice($uniqueIndex)
    {
        if(!$this->items)
        {
            throw new CartIsEmptyException('cart is empty!');
        }

        $item = $this->items[$uniqueIndex];

        return $itemPrice = $item['price']/$item['qty'];
    }
}