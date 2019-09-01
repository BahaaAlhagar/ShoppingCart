<?php

namespace BahaaAlhagar\ShoppingCart;

use Illuminate\Support\Facades\Session;
use Illuminate\Contracts\Events\Dispatcher;
use BahaaAlhagar\ShoppingCart\Exceptions\CartIsEmptyException;
use BahaaAlhagar\ShoppingCart\Exceptions\InvalidQuantityException;
use BahaaAlhagar\ShoppingCart\Exceptions\UnknownUniqueIndexException;

class ShoppingCart
{
    public $items = null;
    public $totalQty;
    public $totalPrice;

    protected $events;

    /**
     * Create a new Skeleton Instance
     */
    public function __construct(Dispatcher $events)
    {
        $oldCart = Session::has('cart') ? Session::get('cart') : null;

        if ($oldCart) {
            $this->items = $oldCart->items;
            $this->totalQty = $oldCart->totalQty;
            $this->totalPrice = $oldCart->totalPrice;
        }
        
        $this->events = $events;
    }

    /**
     * get the cart from the Session
     *
     * @return get the session cart array and the cart object or null
     */
    public function getContent()
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
        // unset the events add the Cart to the Session
        $this->events = null;
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
        // Cart destroyed event
        $this->events->fire('cart.destroyed', $this);

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
    public function add($item, $qty = null, array $options = null)
    {
        // if it is a mutated item by using options then give it another offset
        // else use the item model id
        $uniqueIndex = $options ? $this->createUniqueIndex($item->id, $options) : $item->id;

        $this->validateQty($qty);

        // if there is no certain qty provided by the method then set it to 1
        $Qty = $qty ? $qty : 1;

        // Cart item structure
        $storedItem = ['qty' => 0, 'price' => $item->price, 'options' => $options, 'item' => $item->toArray()];


        // check if the item exists in the cart before
        // and if it exists then get it from the cart
        if ($this->items) {
            if (array_key_exists($uniqueIndex, $this->items)) {
                $storedItem = $this->items[$uniqueIndex];
            }
        }

        // update the cart increase qty and price
        $storedItem['qty'] += $Qty;
        $storedItem['price'] = $item->price * $storedItem['qty'];
        $this->items[$uniqueIndex] = $storedItem;
        $this->totalQty += $Qty;
        $this->totalPrice += ($Qty * $item->price);

        // cartItem added event
        $this->events->fire('cartItem.added', $this->items[$uniqueIndex]);

        // add the cart to the Session
        $this->update();
        
        // return the cart item that has been added
        return $this->items[$uniqueIndex];
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
        $this->validateIndex($uniqueIndex);

        // decrease the qty and the price in the cart
        $this->items[$uniqueIndex]['qty']--;
        $this->items[$uniqueIndex]['price'] -= $this->items[$uniqueIndex]['item']['price'];
        $this->totalQty--;
        $this->totalPrice -= $this->items[$uniqueIndex]['item']['price'];

        // cartItem modified event
        $this->events->fire('cartItem.modified', $this->items[$uniqueIndex]);

        // if the item qty is 0 or less remove the item from Cart
        $this->itemQtyStatusCheck($uniqueIndex);

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

        // cartItem removed event
        $this->events->fire('cartItem.removed', $this->items[$uniqueIndex]);

        // remove the cart item
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
     * get the cart items total price
     *
     * @return the cart total price
     */
    public function total()
    {
        // we need id for the array index
        return $this->totalPrice ? $this->totalPrice : null;
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
        $uniqueIndex = md5($id.serialize($options));
        return $uniqueIndex;
    }

    /**
     * update item in the Cart
     *
     * @param unique item index to modify
     *
     * @param amount to edit
     *
     * @return update the session cart array and the cart object
     */
    public function modify($uniqueIndex, $qty)
    {
        $this->validateQty($qty);

        $this->validateIndex($uniqueIndex);

        // update the cart item
        $this->updateItem($uniqueIndex, $qty);

        // cartItem modified event
        $this->events->fire('cartItem.modified', $this->items[$uniqueIndex]);

        // if the item qty is 0 or less remove the item from Cart
        $this->itemQtyStatusCheck($uniqueIndex);

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
        if (!$this->items) {
            throw new CartIsEmptyException('cart is empty!');
        }

        $item = $this->items[$uniqueIndex];

        return $itemPrice = $item['price']/$item['qty'];
    }

    /**
     * update the item depends on the qty
     *
     * @param unique index
     *
     * @param integer $qty
     *
     * @return void
     */
    public function updateItem($uniqueIndex, $qty)
    {
        // get the current item from the cart
        $currentItem = $this->items[$uniqueIndex];

        // if its the same qty do nothing
        if ($currentItem['qty'] == $qty) {
            return false;
        }

        $currentItemPrice = $this->getItemPrice($uniqueIndex);
        
        $this->totalPrice -= $currentItem['price'];
        $this->totalPrice += ($qty * $currentItemPrice);

        $this->totalQty -= $currentItem['qty'];
        $this->totalQty += $qty;

        $this->items[$uniqueIndex]['qty'] = $qty;

        $this->items[$uniqueIndex]['price'] = $qty * $currentItemPrice;
    }

    /**
     * validate quantity integer and not negative
     *
     * @param  $qty
     *
     * @return void
     */
    public function validateQty($qty)
    {
        if (is_float($qty + 0) || $qty < 0) {
            throw new InvalidQuantityException('Invalid quantity!');
        }
    }

    /**
     * check if the cart contain certain offset or not
     *
     * @param  $uniqueIndex
     *
     * @return void
     */
    public function validateIndex($uniqueIndex)
    {
        // check if the item exists in the cart before
        if (!$this->items) {
            throw new CartIsEmptyException('Cart is empty!');
        }

        if (!array_key_exists($uniqueIndex, $this->items)) {
            throw new UnknownUniqueIndexException("The cart does not contain this index {$uniqueIndex}.");
        }
    }

    /**
     * check if item qty is <= 0 and remove item if true
     *
     * @param  $uniqueIndex
     *
     * @return void
     */
    public function itemQtyStatusCheck($uniqueIndex)
    {
        // if the qty is 0 or less remove the item from Cart
        if ($this->items[$uniqueIndex]['qty'] <= 0) {
            unset($this->items[$uniqueIndex]);
        }
    }
}
