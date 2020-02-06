<?php

namespace App\Providers;

use App\Services\EmailTokenAuthProvider;
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
        $this->app->singleton('Srmklive\Authy\Services\Authy', function () {
            return new EmailTokenAuthProvider();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
