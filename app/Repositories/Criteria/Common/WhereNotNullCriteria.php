<?php

declare(strict_types = 1);

namespace App\Repositories\Criteria\Common;

use App\Repositories\Criteria\Contracts\Criteria;
use Illuminate\Database\Eloquent\Builder;

class WhereNotNullCriteria implements Criteria
{
    public function __construct(protected string|array $columns)
    {
    }

    /**
     * Apply criteria to query in repository.
     *
     * @param Builder $query
     */
    public function apply($query): Builder
    {
        return $query->whereNotNull($this->columns);
    }
}
