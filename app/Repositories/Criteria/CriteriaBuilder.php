<?php

declare(strict_types = 1);

namespace App\Repositories\Criteria;

use App\Repositories\Criteria\Contracts\Criteria;
use App\Repositories\Criteria\Contracts\CriteriaBuilder as CriteriaBuilderContract;

class CriteriaBuilder implements CriteriaBuilderContract
{
    private array $criterion = [];

    public function add(Criteria $criteria): static
    {
        $this->criterion[] = $criteria;

        return $this;
    }

    public function criterion(): array
    {
        return $this->criterion;
    }
}
