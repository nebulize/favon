<?php

namespace Favon\Television\Services;

use Favon\Auth\Models\User;
use Favon\Television\Models\TvSeason;
use Favon\Media\Enumerators\ListStatus;
use Favon\Television\Models\UserTvSeason;
use Favon\Auth\Repositories\UserRepository;
use Favon\Television\Enumerators\ProductionStatus;
use Favon\Television\Repositories\TvShowRepository;
use Favon\Television\Repositories\TvSeasonRepository;
use Favon\Television\Repositories\UserTvShowRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Favon\Television\Repositories\UserTvSeasonRepository;

class ListService
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var UserTvSeasonRepository
     */
    protected $userTvSeasonRepository;

    /**
     * @var UserTvShowRepository
     */
    protected $userTvShowRepository;

    /**
     * @var TvSeasonRepository
     */
    protected $tvSeasonRepository;

    /**
     * @var TvShowRepository
     */
    protected $tvShowRepository;

    /**
     * UserService constructor.
     * @param UserRepository $userRepository
     * @param UserTvShowRepository $userTvShowRepository
     * @param UserTvSeasonRepository $userTvSeasonRepository
     * @param TvSeasonRepository $tvSeasonRepository
     * @param TvShowRepository $tvShowRepository
     */
    public function __construct(UserRepository $userRepository, UserTvShowRepository $userTvShowRepository,
                                UserTvSeasonRepository $userTvSeasonRepository, TvSeasonRepository $tvSeasonRepository,
                                TvShowRepository $tvShowRepository)
    {
        $this->userRepository = $userRepository;
        $this->userTvShowRepository = $userTvShowRepository;
        $this->userTvSeasonRepository = $userTvSeasonRepository;
        $this->tvSeasonRepository = $tvSeasonRepository;
        $this->tvShowRepository = $tvShowRepository;
    }

    /**
     * Add a tv season to a users' list.
     *
     * @param User $user
     * @param TvSeason $tvSeason
     * @param array $values
     */
    public function addTvSeasonToList(User $user, TvSeason $tvSeason, array  $values): void
    {
        $this->userTvSeasonRepository->addTvSeasonToList($user, $tvSeason, $values);
    }

    /**
     * Update a tv season entry in a users' list.
     *
     * @param User $user
     * @param TvSeason $tvSeason
     * @param array $values
     */
    public function updateTvSeasonListStatus(User $user, TvSeason $tvSeason, array $values): void
    {
        $this->userTvSeasonRepository->updateTvSeasonListStatus($user, $tvSeason, $values);
    }

    /**
     * Remove a tv season from a users' list.
     *
     * @param User $user
     * @param TvSeason $tvSeason
     */
    public function removeTvSeasonFromList(User $user, TvSeason $tvSeason): void
    {
        $this->userTvSeasonRepository->removeTvSeasonFromList($user, $tvSeason);
    }

    /**
     * Synchronize tv season and tv show list status in a way that makes sense.
     *
     * @param User $user
     * @param TvSeason $tvSeason
     * @param array|null $values
     */
    public function updateTvShowListStatus(User $user, TvSeason $tvSeason, ?array $values): void
    {
        $tvShow = $this->tvShowRepository->get($tvSeason->tv_show_id, ['withCount' => ['tvSeasons']]);
        $userTvSeasons = $this->userTvSeasonRepository->index(['user_id' => $user->id, 'tv_show_id' => $tvShow->id]);
        if ($userTvSeasons->count() === 0) {
            try {
                $userTvShow = $this->userTvShowRepository->find(['user_id' => $user->id, 'tv_show_id' => $tvShow->id]);
            } catch (ModelNotFoundException $exception) {
                // Nothing to do.
                return;
            }
            $this->userTvShowRepository->delete($userTvShow);

            return;
        }
        $completedSeasons = $userTvSeasons->filter(function (UserTvSeason $userTvSeason) {
            return $userTvSeason->list_status_id === ListStatus::STATUS_COMPLETED;
        });
        $ptwSeasons = $userTvSeasons->filter(function (UserTvSeason $userTvSeason) {
            return $userTvSeason->list_status_id === ListStatus::STATUS_PTW;
        });
        $ratedSeasons = $userTvSeasons->filter(function (UserTvSeason $userTvSeason) {
            return $userTvSeason->score !== null;
        });
        $totalScore = $userTvSeasons->reduce(function (?int $carry, UserTvSeason $item) {
            return $carry + $item->score;
        });
        $score = $ratedSeasons->count() === 0 ? $totalScore : $totalScore / $ratedSeasons->count();

        $status = $values['list_status_id'] ?? $userTvSeasons->last()->list_status_id;

        // If a user completed a tv season he won't necessarily have completed the tv show since
        // there might still be future seasons. Only set to completed if the tv show is completed or cancelled
        // AND the user has set ALL tv seasons as completed.
        if ($status === ListStatus::STATUS_COMPLETED) {
            if (($tvShow->tv_status_id === ProductionStatus::ENDED || $tvShow->tv_status_id === ProductionStatus::CANCELED)
                && $completedSeasons->count() === $tvShow->tv_seasons_count) {
                $status = ListStatus::STATUS_COMPLETED;
            } else {
                $status = ListStatus::STATUS_WATCHING;
            }
        }

        // A tv show should only ever be plan to watch if and only if all tv season entries are also PTW.
        // Example: user completed seasons 1 & 2 of a tv show, and now added the third season to his PTW.
        // Tv show status should still be `watching`
        if ($status === ListStatus::STATUS_PTW) {
            if ($userTvSeasons->count() === $ptwSeasons->count()) {
                $status = ListStatus::STATUS_PTW;
            } else {
                $status = ListStatus::STATUS_WATCHING;
            }
        }

        try {
            $userTvShow = $this->userTvShowRepository->find(['user_id' => $user->id, 'tv_show_id' => $tvShow->id]);
            $this->userTvShowRepository->update($userTvShow, [
                'list_status_id' => $status,
                'score' => $score,
            ]);
        } catch (ModelNotFoundException $exception) {
            $this->userTvShowRepository->create([
                'user_id' =>  $user->id,
                'tv_show_id' => $tvShow->id,
                'list_status_id' => $status,
                'score' => $score,
            ]);
        }
    }
}
