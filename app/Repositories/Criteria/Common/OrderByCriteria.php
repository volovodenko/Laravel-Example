<?php

declare(strict_types = 1);

namespace App\Repositories\Criteria\Common;

use App\Enums\SortDirection;
use App\Repositories\Criteria\Contracts\Criteria;
use Illuminate\Database\Eloquent\Builder;

class OrderByCriteria implements Criteria
{
    public function __construct(
        private string $column,
        private ?SortDirection $direction = null,
    ) {
        if (!$direction) {
            $this->direction = SortDirection::ASC();
        }
    }

    /**
     * Apply criteria to query in repository.
     *
     * @param Builder $query
     */
    public function apply($query): Builder
    {
        return $query->orderBy($this->column, $this->direction->value());
    }
}
