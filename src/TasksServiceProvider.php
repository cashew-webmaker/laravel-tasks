<?php

namespace CashewDigital;


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
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'tasks');

        $this->publishAssets();

        $this->registerCommands();
    }

    /**
     * Publish datatables assets.
     */
    protected function publishAssets()
    {

    }

    /**
     * Register datatables commands.
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

    }

}