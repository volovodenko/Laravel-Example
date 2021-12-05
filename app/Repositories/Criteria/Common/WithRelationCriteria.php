<?php

declare(strict_types = 1);

namespace App\Repositories\Criteria\Common;

use App\Repositories\Criteria\Contracts\Criteria;
use Illuminate\Database\Eloquent\Builder;

class WithRelationCriteria implements Criteria
{
    public function __construct(protected string $relation, protected ?string $without = null)
    {
    }

    /**
     * Apply criteria to query in repository.
     *
     * @param Builder $query
     */
    public function apply($query): Builder
    {
        if (!$this->without) {
            return $query->with($this->relation);
        }

        return $query->with($this->relation, fn ($q) => $q->without($this->without));
    }
}
