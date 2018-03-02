<?php

namespace App\Console\Commands;

use App\Models\TVShow;
use App\Repositories\NetworkRepository;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class ExtractNetworks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'favon:networks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Extract out all networks from current TV shows into their own table.';

    /**
     * Execute the command.
     *
     * @param NetworkRepository $networkRepository
     */
    public function handle(NetworkRepository $networkRepository): void
    {
        DB::connection()->disableQueryLog();
        \DB::disableQueryLog();
        TVShow::chunk(100, function ($tvShows) use ($networkRepository) {
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
