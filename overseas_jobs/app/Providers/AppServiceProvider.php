<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        // $this->app->bind(
        //     'App\services\CustomCryption'
        //     );

        $this->app->bind('App\Providers\services\Test', function ($app) {
            return new Test();
        });
     
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */ 
    public function boot()
    {
        Schema::defaultStringLength(191); 
    }
}
