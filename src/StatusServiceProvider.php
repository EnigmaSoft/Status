<?php

namespace Enigma\Status;

use Illuminate\Support\ServiceProvider;
use Enigma\Status\Controllers\StatusController;

class StatusServiceProvider extends ServiceProvider
{

    protected $commands = [
        'Enigma\Status\Commands\Generate',
    ];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadViewsFrom(__DIR__.'/publishable/resources/views', 'status');

        $this->publishes([
            __DIR__ . '/publishable/config/enigma' => config_path('enigma'),
            __DIR__.'/publishable/resources/views' => resource_path('views/vendor/status')
        ]);
		view()->composer('*', function () {
			return [
				'server' => $app['status']->getServerStatus()->world,
				'count' => $app['status']->StatusOrCount()
			];
		});
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/publishable/config/enigma/status.php', 'enigma.status'
        );
        $this->app->bind('status', function() {
            return new StatusController;
        });
        $this->app->alias('status', StatusController::class);
        $this->commands($this->commands);
        //$this->app->make('Enigma\Status\Controllers\StatusController');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['status', StatusController::class];
    }
}
