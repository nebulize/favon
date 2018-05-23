<?php

namespace App\Console\Commands;

use App\Jobs\UpdateGenres;
use App\Models\TVShow;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateGenresCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'favon:tv:genres:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Completely refetch all genre information';

    /**
     * Execute the command
     */
    public function handle(): void
    {
        DB::connection()->disableQueryLog();
        \DB::disableQueryLog();
        TVShow::chunk(100, function ($tvShows) {
            foreach ($tvShows as $tvShow) {
                UpdateGenres::dispatch($tvShow->id);
            }
        });
    }
}
