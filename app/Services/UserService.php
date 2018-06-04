<?php

namespace App\Services;

use App\Models\TVShow;
use App\Models\User;
use App\Models\UserTvSeason;
use App\Repositories\UserRepository;
use App\Repositories\UserTvSeasonRepository;
use App\Repositories\UserTvShowRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserService
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
     * UserService constructor.
     *
     * @param UserRepository $userRepository
     * @param UserTvShowRepository $userTvShowRepository
     * @param UserTvSeasonRepository $userTvSeasonRepository
     */
    public function __construct(UserRepository $userRepository, UserTvShowRepository $userTvShowRepository,
                                UserTvSeasonRepository $userTvSeasonRepository)
    {
        $this->userRepository = $userRepository;
        $this->userTvShowRepository = $userTvShowRepository;
        $this->userTvSeasonRepository = $userTvSeasonRepository;
    }

    public function updateTvShowListStatus(User $user, TVShow $tvShow, ?string $status): void
    {
        $userTvSeasons = $this->userTvSeasonRepository->index(['user_id' => $user->id, 'tv_show_id' => $tvShow->id]);
        if ($userTvSeasons->count() === 0) {
            $this->userTvShowRepository->delete($user->id, $tvShow->id);
            return;
        }
        $completedSeasons = $userTvSeasons->filter(function (UserTvSeason $userTvSeason) {
            return $userTvSeason->status === User::STATUS_COMPLETED;
        });
        $totalScore = $userTvSeasons->reduce(function (?int $carry, UserTvSeason $item) {
            return $carry + $item->score;
        });
        $score = $totalScore / $userTvSeasons->count();
        // If a user completed a tv season he won't necessarily have completed the tv show since
        // there might still be future seasons. Only set to completed if the tv show is completed or cancelled
        // AND the user has set ALL tv seasons as completed.
        if ($status === User::STATUS_COMPLETED) {
            if (($tvShow->status === 'Completed' || $tvShow->status === 'Cancelled')
                && $completedSeasons->count() === $tvShow->tv_seasons_count) {
                $status = User::STATUS_COMPLETED;
            } else {
                $status = User::STATUS_WATCHING;
            }
        }
        try {
            $userTvShow = $this->userTvShowRepository->get($user->id, $tvShow->id);
            $this->userTvShowRepository->update($userTvShow, [
                'status' => $status,
                'score' => $score,
            ]);
        } catch (ModelNotFoundException $e) {
            $this->userTvShowRepository->create([
                'user_id' =>  $user->id,
                'tv_show_id' => $tvShow->id,
                'status' => $status,
                'score' => $score,
            ]);
        }
    }

}
