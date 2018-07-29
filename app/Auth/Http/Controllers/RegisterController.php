<?php

namespace Favon\Http\Controllers\Auth;

use Favon\Auth\Models\User;
use Favon\Tv\Services\TvService;
use Illuminate\Http\Request;
use Favon\Application\Http\Controller;
use Favon\Auth\Repositories\UserRepository;
use Favon\Auth\Http\Requests\RegisterRequest;
use Illuminate\Auth\Events\Registered;
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
    public $redirectTo = '/tv/seasonal';

    /**
     * @var TvService
     */
    protected $tvService;

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * RegisterController constructor.
     * @param TvService $tvService
     * @param UserRepository $userRepository
     */
    public function __construct(TvService $tvService, UserRepository $userRepository)
    {
        $this->tvService = $tvService;
        $this->userRepository = $userRepository;
        $this->middleware('guest');
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        return view('auth.register', [
            'banner' => $this->tvService->getBanner(),
        ]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param RegisterRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function register(RegisterRequest $request)
    {
        event(new Registered($user = $this->create($request->all())));
        $this->guard()->login($user);

        return $this->registered($request, $user);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data): User
    {
        return $this->userRepository->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    /**
     * The user has been registered.
     *
     * @param User $user
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function registered(User $user)
    {
        return view('auth.notifications', [
            'banner' => $this->tvService->getBanner(),
            'user' => $user,
        ]);
    }
}
