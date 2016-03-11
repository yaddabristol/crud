<?php

namespace Yadda;

use Illuminate\Support\ServiceProvider;

class CrudServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/views', 'crud');
    }

    public function register()
    {

    }
}
