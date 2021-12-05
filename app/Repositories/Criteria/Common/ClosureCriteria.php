<?php

declare(strict_types = 1);

namespace App\Repositories\Criteria\Common;

use App\Repositories\Criteria\Contracts\Criteria;
use Illuminate\Database\Eloquent\Builder;

class ClosureCriteria implements Criteria
{
    /**
     * @var Criteria[]
     */
    private array $criterion;

    public function __construct(Criteria ...$criterion)
    {
        $this->criterion = $criterion;
    }

    /**
     * Apply criteria to query in repository.
     *
     * @param Builder $query
     */
    public function apply($query): Builder
    {
        return $query->where(function (Builder $query) {
            foreach ($this->criterion as $criteria) {
                $criteria->apply($query);
            }

            return $query;
        });
    }
}
