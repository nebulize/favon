<?php

namespace App\Console\Commands;

use App\Repositories\NetworkRepository;
use App\Repositories\TvShowRepository;
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
     * @param TvShowRepository $tvShowRepository
     * @param NetworkRepository $networkRepository
     */
    public function handle(TvShowRepository $tvShowRepository, NetworkRepository $networkRepository): void
    {
        DB::connection()->disableQueryLog();
        $tvShows = $tvShowRepository->index();
        foreach ($tvShows as $tvShow) {
            $networks = explode(', ', $tvShow->network);
            foreach ($networks as $nw) {
                try {
                    $network = $networkRepository->find([
                        'name' => $nw
                    ]);
                } catch (ModelNotFoundException $e) {
                    $network = $networkRepository->create([
                        'name' => $nw
                    ]);
                }
                try  {
                    $tvShow->networks()->attach($network);
                } catch (QueryException $e){
                    // Nothing to do
                }
            }
        }
    }
}
