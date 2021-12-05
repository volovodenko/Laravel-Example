<?php

declare(strict_types = 1);

namespace App\Traits;

use App\Enums\SparePartSearchSortBy;

trait SparePartQueryParamsTrait
{
    use QueryParamsTrait;

    protected function sortBy(): ?SparePartSearchSortBy
    {
        $sortBy = $this->toString(request()->query('sort_by'));

        if (!$sortBy || !SparePartSearchSortBy::isValidValue($sortBy)) {
            return null;
        }

        return new SparePartSearchSortBy($sortBy);
    }
}
