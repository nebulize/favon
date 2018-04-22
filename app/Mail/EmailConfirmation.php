<?php

namespace App\Mail;

use App\Models\User;

class EmailConfirmation extends MjmlMailable
{
    /**
     * @var User
     */
    protected $user;

    /**
     * EmailConfirmation constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('Welcome to Favon - Please confirm your Email')
            ->view('emails.confirm-email')
            ->with([
            'user' => $this->user
        ]);
    }
}
