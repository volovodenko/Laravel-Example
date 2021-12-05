<?php

declare(strict_types = 1);

namespace App\Repositories\Criteria\Common;

use App\Repositories\Criteria\Contracts\Criteria;
use Illuminate\Database\Eloquent\Builder;

class WhereInCriteria implements Criteria
{
    public function __construct(protected string $column, protected $values)
    {
    }

    /**
     * Apply criteria to query in repository.
     *
     * @param Builder $query
     */
    public function apply($query): Builder
    {
        return $query->whereIn($this->column, $this->values);
    }
}
