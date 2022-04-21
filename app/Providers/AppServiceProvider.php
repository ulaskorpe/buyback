<?php

namespace App\Providers;

use App\Models\Order;
use App\Models\OrderReturn;
use App\Observers\OrderObserver;
use App\Observers\ReturnOrderObserver;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('path.public', function() {
            return realpath(base_path().'/../public_html');
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
         Schema::defaultStringLength(255);
        Order::observe(OrderObserver::class);
       OrderReturn::observe(ReturnOrderObserver::class);

    }
}
