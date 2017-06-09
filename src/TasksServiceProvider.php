<?php

namespace Cashewdigital;


use Illuminate\Support\ServiceProvider;

class TasksServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        // Load & Publish Views
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'tasks');
        $this->publishes([
            __DIR__.'/resources/views' => base_path('resources/views/tasks'),
        ], 'tasks-views');

        // Load Routes
        // We can definitely use loadRoutesFrom() method, but using controllers allow us to use route:cache. 100x speed
        $this->app['router']->group(['namespace' => 'Cashewdigital\Http\Controllers'], function () {
            require __DIR__ . '/routes/web.php';
        });

        // Publish JS
        $this->publishes([
            __DIR__ . '/assets/js/topublicjs' => public_path('js'),
        ]);

        // Load Migrations
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

    }

    /**
     * Register Passport's migration files.
     *
     * @return void
     */
    protected function registerMigrations()
    {
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }

    /**
     * Publish assets.
     */
    protected function publishAssets()
    {

    }

    /**
     * Register commands.
     */
    protected function registerCommands()
    {

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('tasks', function ($app) {
            //
        });
    }

}