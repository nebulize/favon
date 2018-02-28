<?php

namespace App\Providers;

use App\Http\Adapters\OMDBAdapter;
use App\Http\Adapters\TMDBAdapter;
use App\Http\Adapters\TVDBAdapter;
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
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(TMDBAdapter::class, function ($app) {
            return new TMDBAdapter(3, Rate::SECOND);
        });
        $this->app->singleton(OMDBAdapter::class, function ($app) {
            return new OMDBAdapter(2, Rate::SECOND);
        });
        $this->app->singleton(TVDBAdapter::class, function ($app) {
            return new TVDBAdapter(2, Rate::SECOND);
        });
    }
}
