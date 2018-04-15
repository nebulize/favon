<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\TvSeasonRepository;
use App\Repositories\TvShowRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/tv/seasonal';

    /**
     * @var TvShowRepository
     */
    protected $tvShowRepository;

    /**
     * @var TvSeasonRepository
     */
    protected $tvSeasonRepository;

    /**
     * LoginController constructor.
     * @param TvShowRepository $tvShowRepository
     * @param TvSeasonRepository $tvSeasonRepository
     */
    public function __construct(TvShowRepository $tvShowRepository, TvSeasonRepository $tvSeasonRepository)
    {
        $this->tvShowRepository = $tvShowRepository;
        $this->tvSeasonRepository = $tvSeasonRepository;
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        $popularShows = $this->tvShowRepository->index([
            'orderBy' => ['popularity', 'DESC'],
            'limit' => 10,
        ]);
        $selected = $popularShows->random();
        try {
            $latestSeason = $this->tvSeasonRepository->find([
                'tv_show_id' => $selected->id,
                'orderBy' => ['number', 'DESC'],
            ]);
            $banner = $latestSeason->banner ?? $selected->banner;
        } catch (ModelNotFoundException $e) {
            $banner = $selected->banner;
        }

        return view('auth.login', [
            'banner' => $banner
        ]);
    }
}
