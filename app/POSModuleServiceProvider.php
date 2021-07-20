<?php

namespace HDSSolutions\Laravel;

use HDSSolutions\Laravel\Modules\ModuleServiceProvider;

class POSModuleServiceProvider extends ModuleServiceProvider {

    protected array $middlewares = [
        \HDSSolutions\Laravel\Http\Middleware\POSMenu::class,
    ];

    private $commands = [
        // \HDSSolutions\Laravel\Commands\SomeCommand::class,
    ];

    public function bootEnv():void {
        // enable config override
        $this->publishes([
            module_path('config/pos.php') => config_path('pos.php'),
        ], 'pos.config');

        // load routes
        $this->loadRoutesFrom( module_path('routes/pos.php') );
        // load views
        $this->loadViewsFrom( module_path('resources/views'), 'pos' );
        // load translations
        $this->loadTranslationsFrom( module_path('resources/lang'), 'pos' );
        // load migrations
        $this->loadMigrationsFrom( module_path('database/migrations') );
        // load seeders
        $this->loadSeedersFrom( module_path('database/seeders') );
    }

    public function register() {
        // register helpers
        if (file_exists($helpers = realpath(__DIR__.'/helpers.php')))
            //
            require_once $helpers;
        // register singleton
        app()->singleton(POS::class, fn() => new POS);
        // register commands
        $this->commands( $this->commands );
        // merge configuration
        $this->mergeConfigFrom( module_path('config/pos.php'), 'pos' );
    }

}
