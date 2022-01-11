<?php

namespace LaravelAdorable;

use Illuminate\Support\ServiceProvider;

class LaravelAdorableServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPublishing();
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    private function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/adorable.php' => config_path('adorable.php'),
            ], 'laraveladorable-config');
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/adorable.php', 'adorable'
        );
        $this->app->singleton(\LaravelAdorable\Facades\LaravelAdorable::class, \LaravelAdorable\Adorable\LaravelAdorable::class);
    }
}
