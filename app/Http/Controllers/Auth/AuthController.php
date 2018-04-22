<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\UpdateNotificationsRequest;
use App\Repositories\UserRepository;
use App\Services\TvService;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * @var TvService
     */
    protected $tvService;

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * AuthController constructor.
     * @param UserRepository $userRepository
     * @param TvService $tvService
     */
    public function __construct(UserRepository $userRepository, TvService $tvService)
    {
        $this->tvService = $tvService;
        $this->userRepository = $userRepository;
    }

    /**
     * Update notification settings for the current user.
     *
     * @param UpdateNotificationsRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function notifications(UpdateNotificationsRequest $request)
    {
        $user = $request->user();
        $user->fill($request->getNotificationSettings());
        $user->save();
        flash()->success('Your notifications settings have been successfully saved.');

        return view('auth.notifications', [
            'banner' => $this->tvService->getBanner(),
            'user' => $user,
        ]);
    }

    public function confirmEmail($token)
    {
        try {
            $user = $this->userRepository->find(['email_token' => $token]);
            $user->verified = true;
            $user->email_token = null;
            $user->save();
        } catch (ModelNotFoundException $e) {
            return abort(404);
        }

        return view('auth.confirmed', [
            'banner' => $this->tvService->getBanner(),
        ]);
    }
}
