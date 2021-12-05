<?php

declare(strict_types = 1);

namespace App\Repositories;

use App\Repositories\Criteria\Contracts\Criteria;
use App\Repositories\Criteria\Contracts\CriteriaBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

abstract class BaseRepository
{
    protected int $perPage;

    public function __construct()
    {
        $this->perPage = config('settings.pagination.per_page');
    }

    public function findOrFail(CriteriaBuilder $criteriaBuilder): Model
    {
        return $this->getBaseQuery($criteriaBuilder)->firstOrFail();
    }

    public function find(CriteriaBuilder $criteriaBuilder): ?Model
    {
        return $this->getBaseQuery($criteriaBuilder)->first();
    }

    public function exists(CriteriaBuilder $criteriaBuilder): bool
    {
        return $this->getBaseQuery($criteriaBuilder)->exists();
    }

    public function firstOrCreate(CriteriaBuilder $criteriaBuilder, array $condition, array $values = []): Model
    {
        return $this->getBaseQuery($criteriaBuilder)->firstOrCreate($condition, $values);
    }

    public function get(CriteriaBuilder $criteriaBuilder): Collection
    {
        return $this->getBaseQuery($criteriaBuilder)->get();
    }

    public function count(CriteriaBuilder $criteriaBuilder): int
    {
        return $this->getBaseQuery($criteriaBuilder)->count('id');
    }

    abstract public function newModel(): Model;

    protected function getBaseQuery(?CriteriaBuilder $criteriaBuilder = null): Builder
    {
        $query = $this->newModel()->newQuery();

        if (!$criteriaBuilder) {
            return $query;
        }

        return $this->applyCriterion($query, $criteriaBuilder);
    }

    /**
     * @param BelongsToMany|Builder $query
     *
     * @return BelongsToMany|Builder
     */
    protected function applyCriterion($query, CriteriaBuilder $criteriaBuilder)
    {
        /** @var Criteria $criteria */
        foreach ($criteriaBuilder->criterion() as $criteria) {
            $criteria->apply($query);
        }

        return $query;
    }
}
