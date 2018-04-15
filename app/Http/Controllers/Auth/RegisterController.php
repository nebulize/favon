<?php

namespace App\Http\Controllers\Auth;

use App\Repositories\TvSeasonRepository;
use App\Repositories\TvShowRepository;
use App\Repositories\UserRepository;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * RegisterController constructor.
     * @param TvShowRepository $tvShowRepository
     * @param TvSeasonRepository $tvSeasonRepository
     * @param UserRepository $userRepository
     */
    public function __construct(TvShowRepository $tvShowRepository, TvSeasonRepository $tvSeasonRepository,
                                UserRepository $userRepository)
    {
        $this->tvShowRepository = $tvShowRepository;
        $this->tvSeasonRepository = $tvSeasonRepository;
        $this->userRepository = $userRepository;
        $this->middleware('guest');
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
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

        return view('auth.register', [
            'banner' => $banner
        ]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|alpha_dash|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return $this->userRepository->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
}
