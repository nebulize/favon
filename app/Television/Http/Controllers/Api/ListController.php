<?php

namespace Favon\Television\Http\Controllers\Api;

use Illuminate\Http\Request;
use Favon\Application\Http\Controller;
use Favon\Television\Services\ListService;
use Favon\Television\Repositories\TvSeasonRepository;
use Favon\Television\Http\Requests\StoreTvSeasonListEntryRequest;

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
        $tvSeason = $this->tvSeasonRepository->get((int) $request->get('tv_season_id'));
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
        $this->listService->updateTvSeasonListStatus($user, $tvSeason, $request->values());
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
