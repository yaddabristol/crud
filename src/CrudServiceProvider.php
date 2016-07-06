<?php

namespace Yaddabristol\Crud;

use Illuminate\Support\ServiceProvider;

use Yaddabristol\Crud\Classes\CrudManager;

class CrudServiceProvider extends ServiceProvider
{

    public function provides()
    {
        return array('crud.manager');
    }

    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/Views', 'crud');

        $this->publishes([
            __DIR__.'/Config/crud.php' => config_path('crud.php'),
            __DIR__.'/Assets/js/crud.js' => public_path('crud/crud.js'),
        ], 'config');
    }

    public function register()
    {       
        $this->app->singleton('crud.manager', function() {
            return new CrudManager();
        });    
    }
}
