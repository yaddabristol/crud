<?php

namespace Yaddabristol\Crud;

use Illuminate\Support\ServiceProvider;

class CrudServiceProvider extends ServiceProvider
{

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
        
    }
}
