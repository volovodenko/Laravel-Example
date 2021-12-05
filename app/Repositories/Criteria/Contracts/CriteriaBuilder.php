<?php

declare(strict_types = 1);

namespace App\Repositories\Criteria\Contracts;

interface CriteriaBuilder
{
    public function add(Criteria $criteria): static;

    public function criterion(): array;
}
