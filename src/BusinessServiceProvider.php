<?php

namespace Solunes\Business;

use Illuminate\Support\ServiceProvider;

class BusinessServiceProvider extends ServiceProvider {

    protected $defer = false;

    public function boot() {
        /* Publicar Elementos */
        $this->publishes([
            __DIR__ . '/config' => config_path()
        ], 'config');
        $this->publishes([
            __DIR__.'/assets' => public_path('assets/business'),
        ], 'assets');

        /* Cargar Traducciones */
        $this->loadTranslationsFrom(__DIR__.'/lang', 'business');

        /* Cargar Vistas */
        $this->loadViewsFrom(__DIR__ . '/views', 'business');
    }


    public function register() {
        /* Registrar ServiceProvider Internos */
        $this->app->register('Rossjcooper\LaravelHubSpot\HubSpotServiceProvider');

        /* Registrar Alias */
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('HubSpot', 'Rossjcooper\LaravelHubSpot\Facades\HubSpot');

        $loader->alias('Business', '\Solunes\Business\App\Helpers\Business');
        $loader->alias('CustomBusiness', '\Solunes\Business\App\Helpers\CustomBusiness');

        /* Comandos de Consola */
        $this->commands([
            //\Solunes\Business\App\Console\AccountCheck::class,
        ]);

        $this->mergeConfigFrom(
            __DIR__ . '/config/business.php', 'business'
        );
    }
    
}
