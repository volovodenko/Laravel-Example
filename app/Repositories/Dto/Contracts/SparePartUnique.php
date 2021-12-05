<?php

declare(strict_types = 1);

namespace App\Repositories\Dto\Contracts;

use App\Models\User;

interface SparePartUnique
{
    public function seller(): User;

    public function articleNumber(): string;
}
