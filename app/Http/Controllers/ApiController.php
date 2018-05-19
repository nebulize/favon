<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Repositories\TvSeasonRepository;
use App\Http\Requests\StoreTvSeasonListEntryRequest;

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
            'progress' => (int) $request->get('progress'),
            'score' => (int) $request->get('score'),
        ]);

        return $user;
    }

    public function updateTvSeasonListStatus(StoreTvSeasonListEntryRequest $request, string $id)
    {
        $user = $request->user();
        $tvSeason = $this->tvSeasonRepository->get((int) $id);
        $this->userRepository->updateTvSeasonListStatus($user, $tvSeason, [
            'status' => $request->get('status'),
            'progress' => (int) $request->get('progress'),
            'score' => (int) $request->get('score'),
        ]);

        return $user;
    }

    public function removeTvSeasonFromList(Request $request, string $id)
    {
        $user = $request->user();
        $tvSeason = $this->tvSeasonRepository->get((int) $id);
        $this->userRepository->removeTvSeasonFromList($user, $tvSeason);

        return $user;
    }
}
