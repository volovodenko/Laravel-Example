<?php

declare(strict_types = 1);

namespace App\Jobs\Binotel;

use App\Jobs\BaseJob;
use App\Models\UserProfile;

abstract class BaseBinotelJob extends BaseJob
{
    public $afterCommit = true;

    public $queue = 'default';

    public function __construct(
        protected UserProfile $userProfile,
    ) {
    }
}
