<?php

namespace App\Providers;

use App\Http\Adapters\OMDBAdapter;
use App\Http\Adapters\TMDBAdapter;
use App\Http\Adapters\TVDBAdapter;
use App\Http\Clients\OMDBClient;
use App\Http\Clients\TMDBClient;
use App\Http\Clients\TVDBClient;
use bandwidthThrottle\tokenBucket\Rate;
use Illuminate\Foundation\Application;
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
    $tmdbClient = $this->app->make(TMDBClient::class);
    $omdbClient = $this->app->make(OMDBClient::class);
    $tvdbClient = $this->app->make(TVDBClient::class);
    $this->app->singleton(TMDBClient::class, function (Application $app) use ($tmdbClient) {
      return $tmdbClient;
    });
    $this->app->singleton(OMDBClient::class, function (Application $app) use ($omdbClient) {
      return $omdbClient;
    });
    $this->app->singleton(TVDBClient::class, function (Application $app) use ($tvdbClient) {
      return $tvdbClient;
    });
  }
}
