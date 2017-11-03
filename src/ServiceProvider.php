<?php

namespace JoelHinz\LaravelQuickSlack;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
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
        $this->app->singleton(QuickSlack::class, function () {
            return new QuickSlack();
        });
    }
}
