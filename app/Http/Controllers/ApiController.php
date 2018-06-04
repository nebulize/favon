<?php

namespace App\Http\Controllers;

use App\Repositories\TvShowRepository;
use App\Repositories\UserTvShowRepository;
use App\Services\UserService;
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
     * @var UserTvShowRepository
     */
    protected $userTvShowRepository;

    /**
     * @var TvShowRepository
     */
    protected $tvShowRepository;

    /**
     * @var UserService
     */
    protected $userService;

    /**
     * ApiController constructor.
     *
     * @param TvSeasonRepository $tvSeasonRepository
     * @param UserRepository $userRepository
     * @param UserTvShowRepository $userTvShowRepository
     * @param TvShowRepository $tvShowRepository
     * @param UserService $userService
     */
    public function __construct(TvSeasonRepository $tvSeasonRepository, UserRepository $userRepository,
                                UserTvShowRepository $userTvShowRepository, TvShowRepository $tvShowRepository,
                                UserService $userService)
    {
        $this->tvSeasonRepository = $tvSeasonRepository;
        $this->userRepository = $userRepository;
        $this->userTvShowRepository = $userTvShowRepository;
        $this->tvShowRepository = $tvShowRepository;
        $this->userService = $userService;
    }

    public function addTvSeasonToList(StoreTvSeasonListEntryRequest $request)
    {
        $user = $request->user();
        $tvSeason = $this->tvSeasonRepository->get($request->get('tv_season_id'));
        $tvShow = $this->tvShowRepository->get($tvSeason->tv_show_id, ['withCount' => ['tvSeasons']]);
        $this->userRepository->addTvSeasonToList($user, $tvSeason, [
            'status' => $request->get('status'),
            'progress' => (int)$request->get('progress'),
            'score' => (int)$request->get('score'),
        ]);
        $this->userService->updateTvShowListStatus($user, $tvShow, $request->get('status'));

        return $user;
    }

    public function updateTvSeasonListStatus(StoreTvSeasonListEntryRequest $request, string $id)
    {
        $user = $request->user();
        $tvSeason = $this->tvSeasonRepository->get((int)$id);
        $tvShow = $this->tvShowRepository->get($tvSeason->tv_show_id, ['withCount' => ['tvSeasons']]);
        $this->userRepository->updateTvSeasonListStatus($user, $tvSeason, [
            'status' => $request->get('status'),
            'progress' => (int) $request->get('progress'),
            'score' => (int) $request->get('score'),
        ]);
        $this->userService->updateTvShowListStatus($user, $tvShow, $request->get('status'));

        return $user;
    }

    public function removeTvSeasonFromList(Request $request, string $id)
    {
        $user = $request->user();
        $tvSeason = $this->tvSeasonRepository->get((int) $id);
        $tvShow = $this->tvShowRepository->get($tvSeason->tv_show_id, ['withCount' => ['tvSeasons']]);
        $this->userRepository->removeTvSeasonFromList($user, $tvSeason);
        $this->userService->updateTvShowListStatus($user, $tvShow, null);

        return $user;
    }
}
