<?php

namespace Enigma\Theme;

use Illuminate\Support\ServiceProvider;

class ThemeServiceProvider extends ServiceProvider
{

    protected $commands = [];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //dd('test');
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->publishes([
            __DIR__ . '/publishable/config/enigma' => config_path('enigma')
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/publishable/config/enigma/theme.php', 'enigma.theme'
        );
        $this->app->bind('theme', function() {
            return new Theme;
        });
        $this->app->alias('theme', Theme::class);
        $this->commands($this->commands);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['theme', Theme::class];
    }
}
