<?php

declare(strict_types = 1);

namespace App\Filters\Contracts;

use App\Repositories\Criteria\Contracts\Criteria;

interface Filter extends Criteria
{
    /**
     * Return an associative array where key is a method name and value is query alias.
     */
    public function filterMap(): array;
}
