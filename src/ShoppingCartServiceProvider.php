<?php

namespace BahaaAlhagar\ShoppingCart;

use Illuminate\Support\ServiceProvider;

class ShoppingCartServiceProvider extends ServiceProvider
{
    
    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('ShoppingCart', function ($app) {
            $events = $app['events'];
            return new ShoppingCart($events);
        });
    }
}
