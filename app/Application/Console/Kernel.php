<?php

namespace Favon\Application\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('favon:update:persons')->daily();
        $schedule->command('favon:update:shows')->daily();
        $schedule->command('favon:update:tv:popularity')->daily();
        $schedule->command('favon:update:tv:imdb')->daily();
        $schedule->command('favon:update:tv:counts')->daily();
        $schedule->command('favon:update:tv:ratings')->twiceDaily(0, 12);
        $schedule->command('horizon:snapshot')->everyFiveMinutes();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(app_path('Media/Commands'));
        $this->load(app_path('Media/Commands/Cronjobs'));
        $this->load(app_path('Media/Commands/Initialize'));
        $this->load(app_path('Tv/Commands/Initialize'));
        $this->load(app_path('Tv/Commands/Initialize'));
    }
}
