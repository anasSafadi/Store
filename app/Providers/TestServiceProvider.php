<?php

namespace App\Providers;

use App\Services\Printservice;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class TestServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->bind('make_log',function(){
            return new Printservice();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
