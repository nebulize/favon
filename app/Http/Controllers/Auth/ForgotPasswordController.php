<?php

namespace App\Http\Controllers\Auth;

use App\Services\TvService;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * @var TvService
     */
    protected $tvService;

    /**
     * ForgotPasswordController constructor.
     * @param TvService $tvService
     */
    public function __construct(TvService $tvService)
    {
        $this->tvService = $tvService;
        $this->middleware('guest');
    }

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email', [
            'banner' => $this->tvService->getBanner(),
        ]);
    }
}
