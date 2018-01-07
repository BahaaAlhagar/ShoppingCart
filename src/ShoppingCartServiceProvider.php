<?php

namespace BahaaAlhagar\ShoppingCart;

use Illuminate\Support\ServiceProvider;

class ShoppingCartServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        if($this->app->runningInConsole())
        {
            $this->commands([
                    makeShopCommand::class
                ]);
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('ShoppingCart', function ($app)
        {
            $events = $app['events'];
            return new ShoppingCart($events);
        });
    }
}