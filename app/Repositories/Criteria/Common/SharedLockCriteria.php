<?php

declare(strict_types = 1);

namespace App\Repositories\Criteria\Common;

use App\Repositories\Criteria\Contracts\Criteria;
use Illuminate\Database\Eloquent\Builder;

class SharedLockCriteria implements Criteria
{
    /**
     * Apply criteria to query in repository.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function apply($query)
    {
        return $query->sharedLock();
    }
}
