<?php

namespace Favon\Http\Controllers\Auth;

use Favon\Services\TvService;
use Favon\Application\Abstracts\Controller;
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
     * @var TvService
     */
    protected $tvService;

    /**
     * LoginController constructor.
     * @param TvService $tvService
     */
    public function __construct(TvService $tvService)
    {
        $this->tvService = $tvService;
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm(): \Illuminate\Http\Response
    {
        return view('auth.login', [
            'banner' => $this->tvService->getBanner(),
        ]);
    }
}
