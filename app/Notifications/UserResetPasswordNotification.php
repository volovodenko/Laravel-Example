<?php

declare(strict_types = 1);

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;

class UserResetPasswordNotification extends BaseQueueableNotification
{
    public $queue = 'notifications';

    public $afterCommit = true;

    /**
     * Create a new notification instance.
     *
     * @param mixed $token
     */
    public function __construct(
        private string $token,
    ) {
    }

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
            ->subject(trans('notifications.user.reset_password.subject'))
            ->line(trans('notifications.user.reset_password.line_1'))
            ->action(trans('notifications.user.reset_password.action'), route('password.reset', ['token' => $this->token, 'email' => $notifiable->email]))
            ->line(trans('notifications.user.reset_password.line_2'))
            ->line(trans('notifications.user.reset_password.line_3'));
    }
}
