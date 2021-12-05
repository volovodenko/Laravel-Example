<?php

declare(strict_types = 1);

namespace App\Models\Dto;

use App\Exceptions\UserAddSubscriptionDtoException;
use Carbon\CarbonImmutable;

class UserAddSubscriptionDto
{
    public function __construct(
        private CarbonImmutable $startDateTime,
        private int $durationDays,
        private ?string $comment = null,
    ) {
        if ($durationDays < 1) {
            throw new UserAddSubscriptionDtoException();
        }
    }

    public function startDateTime(): CarbonImmutable
    {
        return $this->startDateTime;
    }

    public function endDateTime(): CarbonImmutable
    {
        return $this->startDateTime->addDays($this->durationDays);
    }

    public function comment(): ?string
    {
        return $this->comment;
    }
}
