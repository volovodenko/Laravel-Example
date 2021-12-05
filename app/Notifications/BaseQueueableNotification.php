<?php

declare(strict_types = 1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

abstract class BaseQueueableNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $tries = 1;
}
