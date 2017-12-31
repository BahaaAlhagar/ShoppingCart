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
     * @param string $phrase Phrase to return
     *
     * @return string Returns the phrase passed in
     */
    public function echoPhrase($phrase)
    {
        return $phrase;
    }
}
