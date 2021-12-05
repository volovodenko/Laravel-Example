<?php

declare(strict_types = 1);

namespace App\Repositories\Criteria\Common;

use App\Repositories\Criteria\Contracts\Criteria;
use Illuminate\Database\Eloquent\Builder;

class WhereRawCriteria implements Criteria
{
    public function __construct(protected string $sql, protected array $bindings = [])
    {
    }

    /**
     * Apply criteria to query in repository.
     *
     * @param Builder $query
     */
    public function apply($query): Builder
    {
        return $query->whereRaw($this->sql, $this->bindings);
    }
}
