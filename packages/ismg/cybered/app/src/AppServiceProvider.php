<?php

namespace CyberEd\App;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {   
       // echo __DIR__.'/lang/en/messages.php';die();
       $this->loadTranslationsFrom(__DIR__.'/lang', 'package_lang');
       $this->loadViewsFrom(__DIR__ . '/resources/views', 'view_page');
        $this->loadMigrationsFrom(__DIR__.'/../../core/src/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');
    }
}
