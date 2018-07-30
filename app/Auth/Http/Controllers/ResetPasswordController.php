<?php

namespace Favon\Auth\Http\Controllers;

use Favon\Television\Services\TvService;
use Illuminate\Http\Request;
use Favon\Application\Http\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * @var TvService
     */
    protected $tvService;

    /**
     * ResetPasswordController constructor.
     * @param TvService $tvService
     */
    public function __construct(TvService $tvService)
    {
        $this->tvService = $tvService;
        $this->middleware('guest');
    }

    /**
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.passwords.reset')->with([
            'token' => $token,
            'email' => $request->email,
            'banner' => $this->tvService->getBanner(),
        ]);
    }
}
