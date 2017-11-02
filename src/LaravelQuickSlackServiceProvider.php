<?php

namespace JoelHinz\LaravelQuickSlack;

use Illuminate\Support\ServiceProvider;

class LaravelQuickSlackServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/quick-slack.php' => config_path('quick-slack.php'),
        ]);
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(LaravelQuickSlack::class, function () {
            return new LaravelQuickSlack();
        });
    }
}
