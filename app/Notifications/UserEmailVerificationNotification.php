<?php

declare(strict_types = 1);

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserEmailVerificationNotification extends VerifyEmail implements ShouldQueue
{
    use Queueable;

    public $tries = 1;

    public function __construct()
    {
        $this->queue       = 'notifications';
        $this->afterCommit = true;
    }

    /**
     * Get the verify email notification mail message for the given URL.
     *
     * @param string $url
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    protected function buildMailMessage($url)
    {
        return (new MailMessage())
            ->subject(trans('notifications.user.email_verify.subject'))
            ->line(trans('notifications.user.email_verify.line_1'))
            ->action(trans('notifications.user.email_verify.action'), $url);
    }
}
