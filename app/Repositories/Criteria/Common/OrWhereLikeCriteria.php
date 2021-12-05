<?php

declare(strict_types = 1);

namespace App\Repositories\Criteria\Common;

use Illuminate\Database\Eloquent\Builder;

class OrWhereLikeCriteria extends WhereLikeCriteria
{
    /**
     * Apply criteria to query in repository.
     * 
     * @param Builder $query
     */
    public function apply($query): Builder
    {
        return $query->orWhereRaw("LOWER({$this->fieldName}) ilike ?", ['%' .strtolower($this->value) . '%']);
    }
}
