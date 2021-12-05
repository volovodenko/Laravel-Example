<?php

declare(strict_types = 1);

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;

class UserProfileApprovedNotification extends BaseQueueableNotification
{
    public $queue = 'notifications';

    public $afterCommit = true;

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage())
            ->subject(trans('notifications.user.profile_approved.subject'))
            ->line(trans('notifications.user.profile_approved.line_1'))
            ->action(
                trans('notifications.user.profile_approved.action'),
                route('public.profile.account.profile.index')
            );
    }
}
