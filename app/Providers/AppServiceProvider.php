<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Observers\UserObserver;
use App\Observers\OtpObserver;
use App\Observers\OrderObserver;
use App\Models\User;
use App\Models\Otp;
use App\Models\Order;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        User::observe(UserObserver::class);
        Otp::observe(OtpObserver::class);
        Order::observe(OrderObserver::class);
    }
}
