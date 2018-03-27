<?php

namespace App\Providers;

use App\Http\Clients\OMDBClient;
use App\Http\Clients\TMDBClient;
use App\Http\Clients\TVDBClient;
use App\Http\Adapters\OMDBAdapter;
use App\Http\Adapters\TMDBAdapter;
use App\Http\Adapters\TVDBAdapter;
use Illuminate\Foundation\Application;
use bandwidthThrottle\tokenBucket\Rate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(TMDBAdapter::class, function (Application $app) {
            return new TMDBAdapter(3, Rate::SECOND);
        });
        $this->app->singleton(OMDBAdapter::class, function (Application $app) {
            return new OMDBAdapter(2, Rate::SECOND);
        });
        $this->app->singleton(TVDBAdapter::class, function (Application $app) {
            return new TVDBAdapter(2, Rate::SECOND);
        });
    }
}
