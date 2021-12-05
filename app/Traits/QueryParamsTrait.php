<?php

declare(strict_types = 1);

namespace App\Traits;

use App\Enums\SortDirection;

trait QueryParamsTrait
{
    protected function direction(): SortDirection
    {
        $direction = $this->toString(request()->query('direction'));

        if (!$direction) {
            return SortDirection::DESC();
        }

        $direction = strtoupper($direction);

        return SortDirection::isValidValue($direction)
            ? new SortDirection($direction)
            : SortDirection::DESC();
    }

    private function toString($value): ?string
    {
        if (is_array($value) && isset($value[array_key_last($value)])) {
            return $value[array_key_last($value)];
        }

        if (is_string($value)) {
            return $value;
        }

        return null;
    }
}
