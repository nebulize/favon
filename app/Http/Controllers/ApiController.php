<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTvSeasonListEntryRequest;
use App\Repositories\TvSeasonRepository;
use App\Repositories\UserRepository;

class ApiController extends Controller
{
    /**
     * @var TvSeasonRepository
     */
    protected $tvSeasonRepository;

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * ApiController constructor.
     *
     * @param TvSeasonRepository $tvSeasonRepository
     * @param UserRepository $userRepository
     */
    public function __construct(TvSeasonRepository $tvSeasonRepository, UserRepository $userRepository)
    {
        $this->tvSeasonRepository = $tvSeasonRepository;
        $this->userRepository = $userRepository;
    }

    public function addTvSeasonToList(StoreTvSeasonListEntryRequest $request)
    {
        $user = $request->user();
        $tvSeason = $this->tvSeasonRepository->get($request->get('tv_season_id'));
        $this->userRepository->addTvSeasonToList($user, $tvSeason, [
            'status' => $request->get('status'),
            'progress' => $request->has('progress') ? $request->get('progress') : 0,
        ]);
        return $user;
    }
}
