<?php

declare(strict_types = 1);

namespace App\Repositories\Criteria\Common;

use Illuminate\Database\Eloquent\Builder;

class OrWhereCriteria extends WhereCriteria
{
    /**
     * Apply criteria to query in repository.
     *
     * @param Builder $query
     */
    public function apply($query): Builder
    {
        return $query->orWhere($this->fieldName, $this->operator, $this->value);
    }
}
