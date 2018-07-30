<?php

namespace Favon\Television\Http\Controllers;

use Carbon\Carbon;
use Favon\Application\Http\Controller;
use Favon\Media\Repositories\SeasonRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SeasonalController extends Controller
{
    /**
     * @var SeasonRepository
     */
    protected $seasonRepository;

    /**
     * SeasonalController constructor.
     * @param SeasonRepository $seasonRepository
     */
    public function __construct(SeasonRepository $seasonRepository)
    {
        $this->seasonRepository = $seasonRepository;
    }

    /**
     * Display a specific season (by year and name).
     *
     * @param int|null $year
     * @param string|null $name
     *
     * @throws NotFoundHttpException
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(int $year = null, string $name = null)
    {
        try {
            if ($year === null || $name === null) {
                $season = $this->seasonRepository->find([
                    'date' => Carbon::now(),
                ]);
            } else {
                $season = $this->seasonRepository->find([
                    'year' => $year,
                    'name' => ucfirst($name),
                ]);
            }
            $seasons = $this->seasonRepository->indexAround($season);
        } catch (ModelNotFoundException $exception) {
            throw new NotFoundHttpException();
        }

        return view('tv.seasonal.index', [
            'currentSeason' => $season,
            'seasons' => $seasons,
        ]);
    }
}
