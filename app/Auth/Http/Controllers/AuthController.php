<?php

namespace Favon\Auth\Http\Controllers;

use Favon\Application\Http\Controller;
use Favon\Television\Services\TvService;
use Favon\Auth\Repositories\UserRepository;
use Favon\Auth\Http\Requests\UpdateNotificationsRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
    public function notifications(UpdateNotificationsRequest $request): \Illuminate\Http\RedirectResponse
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

    /**
     * Confirm the users' email via token.
     *
     * @param $token
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function confirmEmail($token)
    {
        try {
            $user = $this->userRepository->find(['email_token' => $token]);
        } catch (ModelNotFoundException $exception) {
            throw new NotFoundHttpException();
        }

        $this->userRepository->update($user, ['verified' => true, 'email_token' => null]);

        return view('auth.confirmed', [
            'banner' => $this->tvService->getBanner(),
        ]);
    }
}
