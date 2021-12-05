<?php

declare(strict_types = 1);

namespace App\Repositories\Criteria\Common;

use App\Repositories\Criteria\Contracts\Criteria;
use Illuminate\Database\Eloquent\Builder;

class WhereCriteria implements Criteria
{
    public function __construct(protected string $fieldName, protected $operator = null, protected $value = null)
    {
    }

    /**
     * Apply criteria to query in repository.
     *
     * @param Builder $query
     */
    public function apply($query): Builder
    {
        return $query->where($this->fieldName, $this->operator, $this->value);
    }
}
