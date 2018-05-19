<?php

namespace App\Listeners;

use Illuminate\Mail\Mailer;
use App\Mail\EmailConfirmation;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendVerificationEmail implements ShouldQueue
{
    protected $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function handle(Registered $event): void
    {
        $user = $event->user;
        $this->mailer->to($user)->send(new EmailConfirmation($user));
    }
}
