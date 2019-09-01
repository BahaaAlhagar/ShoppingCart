<?php

namespace BahaaAlhagar\ShoppingCart\Facades;

use Illuminate\Support\Facades\Facade;

class Cart extends Facade
{
    /**
     * Get the binding in the IoC container
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'ShoppingCart'; // the IoC binding.
    }
}
