<?php

namespace App\Providers;
use App\Lib\Classes\EuclideanCube;
use Illuminate\Support\ServiceProvider;

class CubeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    //bind the RubikCubeInterface with EuclideanCube
    public function register(){
        $this->app->bind('App\Lib\Interfaces\RubikCubeInterface', function () {
            return new EuclideanCube();
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
