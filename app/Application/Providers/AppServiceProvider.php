<?php

namespace Favon\Application\Providers;

use Illuminate\Foundation\Application;
use bandwidthThrottle\tokenBucket\Rate;
use Illuminate\Support\ServiceProvider;
use Favon\Media\Http\Gateways\OmdbGateway;
use Favon\Media\Http\Gateways\TmdbGateway;
use Favon\Media\Http\Gateways\TvdbGateway;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(TmdbGateway::class, function (Application $app) {
            return new TmdbGateway(3, Rate::SECOND);
        });
        $this->app->singleton(OmdbGateway::class, function (Application $app) {
            return new OmdbGateway(2, Rate::SECOND);
        });
        $this->app->singleton(TvdbGateway::class, function (Application $app) {
            return new TvdbGateway(2, Rate::SECOND);
        });
    }
}
