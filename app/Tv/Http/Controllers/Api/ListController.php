<?php

namespace Favon\Tv\Http\Controllers\Api;

use Favon\Application\Abstracts\Controller;
use Favon\Tv\Http\Requests\StoreTvSeasonListEntryRequest;
use Favon\Tv\Repositories\TvSeasonRepository;
use Favon\Tv\Services\ListService;
use Illuminate\Http\Request;

class ListController extends Controller
{
    /**
     * @var ListService
     */
    protected $listService;

    /**
     * @var TvSeasonRepository
     */
    protected $tvSeasonRepository;

    /**
     * ListController constructor.
     * @param ListService $listService
     * @param TvSeasonRepository $tvSeasonRepository
     */
    public function __construct(ListService $listService, TvSeasonRepository $tvSeasonRepository)
    {
        $this->listService = $listService;
        $this->tvSeasonRepository = $tvSeasonRepository;
    }

    /**
     * Store a new tv season in the users' list.
     *
     * @param StoreTvSeasonListEntryRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreTvSeasonListEntryRequest $request): \Illuminate\Http\JsonResponse
    {
        $user = $request->user();
        $tvSeason = $this->tvSeasonRepository->get((int)$request->get('tv_season_id'));
        $this->listService->addTvSeasonToList($user, $tvSeason, $request->values());
        $this->listService->updateTvShowListStatus($user, $tvSeason, $request->values());
        return response()->json($user);
    }

    /**
     * Update a tv season entry in the users' list.
     *
     * @param StoreTvSeasonListEntryRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(StoreTvSeasonListEntryRequest $request, int $id): \Illuminate\Http\JsonResponse
    {
        $user = $request->user();
        $tvSeason = $this->tvSeasonRepository->get($id);
        $this->listService->updateTvSeasonListStatus($user,$tvSeason, $request->values());
        $this->listService->updateTvShowListStatus($user, $tvSeason, $request->values());
        return response()->json($user);
    }

    /**
     * Remove a tv season from a users' list.
     *
     * @param Request $request
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request, int $id): \Illuminate\Http\JsonResponse
    {
        $user = $request->user();
        $tvSeason = $this->tvSeasonRepository->get($id);
        $this->listService->removeTvSeasonFromList($user, $tvSeason);
        $this->listService->updateTvShowListStatus($user, $tvSeason, null);
        return response()->json($user);
    }
}
