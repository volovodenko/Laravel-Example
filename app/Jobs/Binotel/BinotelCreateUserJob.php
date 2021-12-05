<?php

declare(strict_types = 1);

namespace App\Jobs\Binotel;

use App\Services\BinotelService;

class BinotelCreateUserJob extends BaseBinotelJob
{
    public function handle(BinotelService $binotelService)
    {
        $binotelService->createCustomer($this->userProfile);
    }
}
