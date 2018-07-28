<?php

namespace Favon\Application\Providers;

use Favon\Tv\Events\TvSeasonUpdated;
use Favon\Tv\Listeners\UpdateEpisodeCount;
use Illuminate\Auth\Events\Registered;
use Favon\Auth\Listeners\SendVerificationEmail;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
          SendVerificationEmail::class,
        ],
        TvSeasonUpdated::class => [
            UpdateEpisodeCount::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        parent::boot();
        //
    }
}
