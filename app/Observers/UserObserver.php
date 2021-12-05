<?php

declare(strict_types = 1);

namespace App\Observers;

use App\Jobs\Binotel\BinotelUpdateUserJob;
use App\Models\User;

class UserObserver
{
    public function updated(User $user)
    {
        if ($this->canSendDataToBinotel($user)) {
            BinotelUpdateUserJob::dispatch($user->profile);
        }
    }

    private function canSendDataToBinotel(User $user)
    {
        return $user->isDirty([
            'email',
        ]);
    }
}
