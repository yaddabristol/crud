<?php

namespace Yaddabristol\Crud;

use Illuminate\Support\ServiceProvider;

use Yaddabristol\Crud\Classes\CrudManager;

/**
 * Laravel service provider for CRUD
 *
 * @author  Andrew Ellender <andrew@yadda.co.uk>
 * @license MIT
 */
class CrudServiceProvider extends ServiceProvider
{

    /**
     * @todo   Document this!
     * @return array
     */
    public function provides()
    {
        return array('crud.manager');
    }

    /**
     * Bootstrap any application services
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/Views', 'crud');

        $this->publishes([
            __DIR__.'/Config/crud.php' => config_path('crud.php'),
            __DIR__.'/Assets/js/crud.js' => public_path('crud/crud.js'),
        ], 'config');
    }

    /**
     * Register any application services
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('crud.manager', function() {
            return new CrudManager();
        });
    }
}
