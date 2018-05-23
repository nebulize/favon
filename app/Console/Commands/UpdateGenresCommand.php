<?php

namespace App\Console\Commands;

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
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        DB::connection()->disableQueryLog();
        \DB::disableQueryLog();
        TVShow::chunk(100, function ($tvShows) {
            foreach ($tvShows as $tvShow) {
                if ($tvShow->network === null) {
                    continue;
                }
                $networks = explode(', ', $tvShow->network);
                foreach ($networks as $nw) {
                    try {
                        $network = $networkRepository->find(['name' => $nw]);
                    } catch (ModelNotFoundException $e) {
                        $network = $networkRepository->create(['name' => $nw]);
                    }
                    try {
                        $tvShow->networks()->attach($network);
                    } catch (QueryException $e) {
                        // Nothing to do
                    }
                }
            }
        });
    }
}
