<?php

namespace App\Providers;

use Darryldecode\Cart\Cart;
use Illuminate\Support\ServiceProvider;

class WishlistProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton('wishlist', function ($app) {
            $storage = $app['session'];
            $events = $app['events'];
            $instanceName = 'cart_2';
            $sessionKey = '88uuiioo99888';
            return new Cart($storage, $events, $instanceName, $sessionKey, config('shopping_cart'));
        });
    }
}
