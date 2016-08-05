<?php
namespace BrightOak\Serps\Providers;

use BrightOak\Serps\Serps;
use Illuminate\Support\ServiceProvider;

class SerpsProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('laravel-serps-api', function () {
            return new Serps; //Add the proper namespace at the top
        });
    }
}
