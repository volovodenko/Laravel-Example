<?php

declare(strict_types = 1);

namespace App\Repositories\Criteria\SparePart;

use App\Enums\SortDirection;
use App\Enums\SparePartSearchSortBy;
use App\Repositories\Criteria\Contracts\Criteria;
use App\Traits\SparePartQueryParamsTrait;
use Illuminate\Database\Eloquent\Builder;

class SearchSortCriteria implements Criteria
{
    use SparePartQueryParamsTrait;

    private ?SparePartSearchSortBy $sortBy;
    private SortDirection $direction;

    public function __construct()
    {
        $this->sortBy    = $this->sortBy();
        $this->direction = $this->direction();
    }

    /**
     * Apply criteria to query in repository.
     *
     * @param Builder $query
     */
    public function apply($query): Builder
    {
        if (!$this->sortBy) {
            return $query;
        }

        if ($this->sortBy->equals(SparePartSearchSortBy::SELLER_RATING())) {
            return $query->leftJoin('users as seller', 'seller.id', '=', 'spare_parts.seller_id')
                ->orderBy('seller.seller_rating', $this->direction->value());
        }

        return $query->orderBy($this->sortBy->value(), $this->direction->value());
    }
}
