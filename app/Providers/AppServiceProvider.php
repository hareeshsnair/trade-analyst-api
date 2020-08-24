<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// use Carbon\Carbon;
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
        if ($this->app->isLocal()) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Carbon::setWeekStartsAt(Carbon::TUESDAY);
        // Carbon::setWeekEndsAt(Carbon::SUNDAY);

        User::observe(UserObserver::class);
        Otp::observe(OtpObserver::class);
        Order::observe(OrderObserver::class);
    }
}
