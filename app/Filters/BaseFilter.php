<?php

declare(strict_types = 1);

namespace App\Filters;

use App\Exceptions\FilterException;
use App\Filters\Contracts\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class BaseFilter implements Filter
{
    protected Builder $query;

    protected Request $request;

    public function __construct()
    {
        $this->request = app(Request::class);
    }

    /**
     * @param Builder $query
     */
    public function apply($query): Builder
    {
        $this->query = $query;

        $this->applyFilters();

        return $this->query;
    }

    protected function isValidString($string): bool
    {
        return is_string($string) && '' !== $string || strlen($string) <= 255;
    }

    protected function isValidInt($int): bool
    {
        return is_numeric($int) && is_int(+$int);
    }

    private function applyFilters()
    {
        foreach ($this->filters() as $method => $value) {
            if (!method_exists($this, $method)) {
                throw new FilterException('Filter \'' . $method . '\' is declared in \'filterMap\', but it does not exist.');
            }

            is_null($value) ? $this->{$method}() : $this->{$method}($value);
        }
    }

    private function filters(): array
    {
        $filters = [];

        foreach ($this->filterMap() as $method => $queryAlias) {
            $method = is_string($method) ? $method : $queryAlias;

            $queryAliasValue = $this->request->query($queryAlias);

            if (null !== $queryAliasValue) {
                $filters[$method] = $queryAliasValue;
            }
        }

        return $filters;
    }
}
