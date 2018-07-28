<?php

namespace Favon\Auth\Listeners;

use Illuminate\Mail\Mailer;
use Favon\Auth\Mail\EmailConfirmation;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendVerificationEmail implements ShouldQueue
{
    /**
     * @var Mailer
     */
    protected $mailer;

    /**
     * SendVerificationEmail constructor.
     * @param Mailer $mailer
     */
    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Send user verification mail.
     *
     * @param Registered $event
     */
    public function handle(Registered $event): void
    {
        $user = $event->user;
        $this->mailer->to($user)->send(new EmailConfirmation($user));
    }
}
