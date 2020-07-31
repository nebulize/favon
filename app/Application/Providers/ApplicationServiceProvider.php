<?php

namespace Favon\Application\Providers;

use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Illuminate\Support\ServiceProvider;

class ApplicationServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'Application';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'application';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(\Laravel\Dusk\DuskServiceProvider::class);
            $this->app->register(DuskServiceProvider::class);
            $this->app->register(IdeHelperServiceProvider::class);
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
        }
    }
}
