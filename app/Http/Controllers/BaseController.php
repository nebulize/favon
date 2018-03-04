<?php

namespace App\Http\Controllers;

use App\Enumerators\SeasonType;
use App\Repositories\GenreRepository;
use App\Repositories\SeasonRepository;
use App\Repositories\TvSeasonRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    /**
     * @var TvSeasonRepository
     */
    private $tvSeasonRepository;

    /**
     * @var SeasonRepository
     */
    private $seasonRepository;

    /**
     * @var GenreRepository
     */
    private $genreRepository;

    public function __construct(TvSeasonRepository $tvSeasonRepository, SeasonRepository $seasonRepository, GenreRepository $genreRepository)
    {
        $this->tvSeasonRepository = $tvSeasonRepository;
        $this->seasonRepository = $seasonRepository;
        $this->genreRepository = $genreRepository;
    }

    public function index(Request $request, $year, $season)
    {
        try {
                    $season = $this->seasonRepository->find([
                        'year' => (int) $year,
                        'name' => ucfirst($season)
                    ]);
                    $seasons = $this->seasonRepository->index([
                        'around' => $season
                    ]);
                    if ($request->has('sequels') && $request->get('sequels') === 'false')  {
                        $tvSeasons = $this->tvSeasonRepository->index([
                            'seasonal' => true,
                            'sequels' => false,
                            'season' => $season
                        ]);
            } else {
                $tvSeasons = $this->tvSeasonRepository->index([
                    'seasonal' => true,
                    'season' => $season
                ]);
            }
        } catch (ModelNotFoundException $e) {
            abort(404);
        }
        $genres = $this->genreRepository->index();
        return view('tv.seasonal.index', [
            'season' => $season,
            'seasons' => $seasons,
            'tvSeasons' => $tvSeasons,
            'genres' => $genres
        ]);

    }
}
