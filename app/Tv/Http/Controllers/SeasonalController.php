<?php

namespace Favon\Tv\Http\Controllers;

use Carbon\Carbon;
use Favon\Media\Repositories\SeasonRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SeasonalController
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
     * Display the current season.
     *
     * @throws NotFoundHttpException
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        try {
            $season = $this->seasonRepository->find([
                'date' => Carbon::now(),
            ]);
            $seasons = $this->seasonRepository->indexAround($season);
        } catch (ModelNotFoundException $exception) {
            throw new NotFoundHttpException();
        }

        return view('tv.seasonal.index', [
            'currentSeason' => $season,
            'seasons' => $seasons,
        ]);
    }

    /**
     * Display a specific season (by year and name).
     *
     * @param int $year
     * @param string $name
     *
     * @throws NotFoundHttpException
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(int $year, string $name)
    {
        try {
            $season = $this->seasonRepository->find([
                'year' => $year,
                'name' => ucfirst($name),
            ]);
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
