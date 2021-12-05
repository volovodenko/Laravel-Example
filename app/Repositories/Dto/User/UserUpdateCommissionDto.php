<?php

declare(strict_types = 1);

namespace App\Repositories\Dto\User;

class UserUpdateCommissionDto
{
    public function __construct(
        private int $oldDefaultCommission,
        private int $newDefaultCommission,
        private int $oldVipCommission,
        private int $newVipCommission,
    ) {
    }

    public function oldDefaultCommission(): int
    {
        return $this->oldDefaultCommission;
    }

    public function newDefaultCommission(): int
    {
        return $this->newDefaultCommission;
    }

    public function oldVipCommission(): int
    {
        return $this->oldVipCommission;
    }

    public function newVipCommission(): int
    {
        return $this->newVipCommission;
    }
}
