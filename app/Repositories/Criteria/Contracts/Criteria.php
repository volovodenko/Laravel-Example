<?php

declare(strict_types = 1);

namespace App\Repositories\Criteria\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

interface Criteria
{
    /**
     * Apply criteria to query in repository.
     *
     * @param BelongsToMany|Builder $builder
     *
     * @return BelongsToMany|Builder
     */
    public function apply($builder);
}
